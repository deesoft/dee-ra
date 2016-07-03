<?php

namespace classes\url;

use yii\base\Object;
use yii\web\UrlRuleInterface;

/**
 * Description of UrlRule
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UrlRule extends Object implements UrlRuleInterface
{
    public $suffix;

    /**
     * @var boolean a value indicating if parameters should be url encoded.
     */
    public $encodeParams = true;
    public $rules = [];
    private $_routes;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        $this->_routes = [];

        foreach ($this->rules as $pattern => $route) {
            if (preg_match("/^((?:($verbs),)*($verbs))(?:\\s+(.*))?$/", $pattern, $matches)) {
                $methods = explode(',', $matches[1]);
                $pattern = isset($matches[4]) ? $matches[4] : '';
            } else {
                $methods = [];
            }
            list($regex, $variables) = $this->parse($pattern);
            $this->_routes[$regex] = [$route, $methods, $variables];
        }
    }

    protected function parse($pattern, $route, $params, $verbs)
    {
        $pattern = ltrim($pattern, '/');
        if (preg_match_all('~<([\w._-]+):?([^>]+)?>~', $pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
            $regex = '~^';
            $regex2 = '';
            $vars1 = $vars2 = $vars3 = [];
            $offset = 0;
            $i = 1;
            foreach ($matches as $match) {
                if ($match[0][1] > $offset) {
                    $regex .= preg_quote(substr($pattern, $offset, $match[0][1] - $offset), '~');
                    $regex2 .= substr($pattern, $offset, $match[0][1] - $offset);
                }
                $var = $match[1][0];
                $vars2[$var] = $_regex = isset($match[2][0]) ? $match[2][0] : '[^/]+';
                $vars1['d' . $i] = $var;
                $regex .= $vars3["<$var>"] = "(?P<d{$i}>$_regex)";
                
                $regex2 .= "<$var>";
                $offset = $match[0][1] + strlen($match[0][0]);
                $i++;
            }
            if ($offset != strlen($pattern)) {
                $regex .= preg_quote(substr($pattern, $offset), '~');
                $regex2 .= substr($pattern, $offset);
            }
            $regex .= '$~';

            // route params
            $routeParams = [];
            if (strpos($route, '<') !== false && preg_match_all('/<([\w._-]+)>/', $route, $matches)) {
                foreach ($matches[1] as $name) {
                    $routeParams[] = $name;
                }
            }
            // safe pattern
            if (isset($this->_routes['dynamic'][$regex])) {
                $this->_routes['dynamic'][$regex][] = [$route, $params, $verbs, $vars1, $routeParams];
                usort($this->_routes['dynamic'][$regex], function($v1, $v2) {
                    return count($v1[2]) >= count($v2[2]) ? -1 : 1;
                });
            } else {
                $this->_routes['dynamic'][$regex] = [[$route, $params, $verbs, $vars1, $routeParams]];
            }

            if (empty($verbs) || in_array('GET', $verbs)) {
                if (strpos($route, '<') === false) {
                    $this->_routes['static-r'][$route][] = [$regex2, $params, $vars2];
                } else {
                    $this->_routes['dynamic-r']['~^' . strtr($route, $vars3) . '$~'][] = [$regex2, $params, $vars2];
                }
            }
        } else {
            if (isset($this->_routes['static'][$pattern])) {
                $this->_routes['static'][$pattern][] = [$route, $params, $verbs];
                usort($this->_routes['static'][$pattern], function($v1, $v2) {
                    return count($v1[2]) >= count($v2[2]) ? -1 : 1;
                });
            } else {
                $this->_routes['static'][$pattern] = [[$route, $params, $verbs]];
            }

            if (empty($verbs) || in_array('GET', $verbs)) {
                $this->_routes['static-r'][$route][] = [$pattern, $params, []];
            }
        }
    }

    public function createUrl($manager, $route, $params)
    {
        if ($this->_routes['static-r'][$route]) {
            foreach ($this->_routes['static-r'][$route] as $data) {
                list($pattern, $default, $vars) = $data;
                $match = true;
                $p2 = $params;
                foreach ($default as $key => $value) {
                    if (array_key_exists($key, $p2) && $p2[$key] === $value) {
                        unset($p2[$key]);
                    } else {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $p3 = [];
                    foreach ($vars as $n => $regex) {
                        if (isset($p2[$n]) && preg_match("~^$regex$~", $p2[$n])) {
                            $p3["<$n>"] = $this->encodeParams ? urlencode($p2[$n]) : $p2[$n];
                            unset($p2[$n]);
                        } else {
                            $match = false;
                            break;
                        }
                    }
                    if ($match) {
                        $url = trim(strtr($pattern, $p3), '/');
                        if ($url !== '') {
                            $url .= ($this->suffix === null ? $manager->suffix : $this->suffix);
                        }
                        if (!empty($p2) && ($query = http_build_query($p2)) !== '') {
                            $url .= '?' . $query;
                        }
                        return $url;
                    }
                }
            }
        } else {
            foreach ($this->_routes['dynamic-r'] as $regex => $data) {
                if (preg_match($regex, $route, $matches)) {
                    $p2 = $params;
                    
                }
            }
        }
    }

    /**
     *
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $suffix = (string) ($this->suffix === null ? $manager->suffix : $this->suffix);
        if ($suffix !== '' && $pathInfo !== '') {
            $n = strlen($suffix);
            if (substr_compare($pathInfo, $suffix, -$n, $n) === 0) {
                $pathInfo = substr($pathInfo, 0, -$n);
                if ($pathInfo === '') {
                    // suffix alone is not allowed
                    return false;
                }
            } else {
                return false;
            }
        }

        $method = $request->getMethod();
        if (isset($this->_routes['static'][$pathInfo])) {
            foreach ($this->_routes['static'][$pathInfo] as $data) {
                list($route, $params, $verbs) = $data;
                if (empty($verbs) || in_array($method, $verbs)) {
                    return [$route, $params];
                }
            }
        }

        foreach ($this->_routes['dynamic'] as $regex => $patterns) {
            if (preg_match($regex, $pathInfo, $matches)) {
                foreach ($patterns as $data) {
                    list($route, $params, $verbs, $vars, $routeParams) = $data;
                    if (!empty($verbs) && !in_array($method, $verbs)) {
                        continue;
                    }
                    $p1 = $p2 = [];
                    foreach ($vars as $ph => $n) {
                        if (in_array($n, $routeParams)) {
                            $p2["<$n>"] = $matches[$ph];
                        } else {
                            $p1[$n] = $matches[$ph];
                        }
                    }
                    $route = strtr($route, $p2);
                    return [$route, $p1 + $params];
                }
            }
        }
        return false;
    }
}
