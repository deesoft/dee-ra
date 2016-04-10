<?php

namespace classes\jeasyui;

/**
 * Description of Easyui
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Easyui
{
    static $plugins = [
        'datagrid' => 'table'
    ];

    public static function plugin($name, $options = [], $htmlOptions = [], $content = '')
    {
        $tag = isset(static::$plugins[$name]) ? static::$plugins[$name] : 'div';
        $htmlOptions['easyui'] = $options;
        $htmlOptions['easyui']['_plugin'] = $name;
        return Html::tag($tag, $content, $htmlOptions);
    }

    public static function datagrid($options = [], $htmlOptions = [], $content = '')
    {
        return static::plugin('datagrid', $options, $htmlOptions, $content);
    }
}
