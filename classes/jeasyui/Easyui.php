<?php

namespace classes\jeasyui;

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * Description of Easyui
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Easyui
{
    public static $plugins = [
        'datagrid' => 'table'
    ];
    /**
     * @var \yii\web\View
     */
    private static $_view;
    private static $_stacks = [];

    /**
     *
     * @return \yii\web\View
     */
    public static function getView()
    {
        if (self::$_view === null) {
            self::$_view = Yii::$app->controller ? Yii::$app->controller->getView() : Yii::$app->getView();
        }
        return self::$_view;
    }

    protected static function registerPlugin($name, &$options = [], $easyuiOptions = [])
    {
        $view = self::getView();
        $name = strtolower($name);
        if (empty($options['id'])) {
            $options['data-options'] = $easyuiOptions;
            Html::addCssClass($options, 'easyui-' . $name);
        } else {
            $opts = empty($easyuiOptions) ? '{}' : Json::htmlEncode($easyuiOptions);
            $view->registerJs("jQuery('#{$options['id']}').{$name}($opts);");
        }
        EasyuiAsset::register($view);
    }

    public static function plugin($name, $options = [], $easyuiOptions = [], $content = '')
    {
        $tag = isset(static::$plugins[$name]) ? static::$plugins[$name] : 'div';
        static::registerPlugin($name, $options, $easyuiOptions);
        return Html::tag($tag, $content, $options);
    }

    public static function begin($name, $options = [], $easyuiOptions = [])
    {
        self::$_stacks[] = [$name, $options, $easyuiOptions];
        ob_start();
        ob_implicit_flush(false);
    }

    public static function end()
    {
        $content = ob_get_clean();
        list($name, $options, $easyuiOptions) = array_pop(self::$_stacks);
        echo static::plugin($name, $options, $easyuiOptions, $content);
    }

    public static function __callStatic($name, $arguments)
    {
        if (strncasecmp($name, 'begin', 5) === 0) {
            array_unshift($arguments, substr($name, 5));
            call_user_func_array([get_called_class(), 'begin'], $arguments);
        } elseif (strncasecmp($name, 'end', 3) === 0) {
            echo static::end();
        } else {
            array_unshift($arguments, $name);
            return call_user_func_array([get_called_class(), 'plugin'], $arguments);
        }
    }

    public static function datagrid($options = [], $easyuiOptions = [], $content = '')
    {
        return static::plugin('datagrid', $options, $easyuiOptions, $content);
    }

    public function beginDatagrid($options = [], $easyuiOptions = [])
    {
        static::begin('datagrid', $options, $easyuiOptions);
    }

    public static function panel($options = [], $easyuiOptions = [], $content = '')
    {
        return static::plugin('panel', $options, $easyuiOptions, $content);
    }

    public function beginPanel($options = [], $easyuiOptions = [])
    {
        static::begin('panel', $options, $easyuiOptions);
    }
}
