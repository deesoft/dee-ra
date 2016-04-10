<?php

namespace classes\jeasyui;

/**
 * Description of Widget
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Widget extends \yii\base\Widget
{
    public $plugin;
    public $options = [];
    public $clientOptions = [];
    public static $plugins = [
        'datagrid'=>'table',
        ''
    ];
    public function init()
    {
        if(!isset($this->options['id'])){
            $this->options['id'] = $this->getId();
        }
    }
}
