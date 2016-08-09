<?php

namespace app\classes;

use yii\helpers\Html;

/**
 * Description of Formatter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Formatter extends \yii\i18n\Formatter
{
    public static $links;
    private static $_cached = [];

    public function asLink($value, $options = [])
    {
        return preg_replace_callback('/\[((\d{3})\.[^\]]+)\]/', function($match) use($options) {
            if (isset(static::$links[$match[2]])) {
                if (isset(self::$_cached[$match[1]])) {
                    return Html::a($match[1], self::$_cached[$match[1]], $options);
                }
                $field = $match[2] < 200 ? 'code' : 'number';
                list($class, $url) = static::$links[$match[2]];
                if (($model = $class::findOne([$field => $match[1]])) !== null) {
                    if (!is_array($url)) {
                        $url = [$url];
                    }
                    $url[0] = '/' . ltrim($url[0], '/');
                    $url['id'] = $model->id;
                    self::$_cached[$match[1]] = $url;
                    return Html::a($match[1], $url, $options);
                }
            }
            return $match[0];
        }, $value);
    }
}

Formatter::$links = require __DIR__ . '/links.php';
