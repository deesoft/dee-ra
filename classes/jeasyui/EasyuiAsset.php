<?php

namespace classes\jeasyui;

use yii\web\AssetBundle;

/**
 * Description of EasyuiAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class EasyuiAsset extends AssetBundle
{
    public $sourcePath = '@classes/jeasyui/assets';
    public $js = [
        'jquery.easyui.min.js',
    ];
    public $css = [
        'kube.css',
        'main.css',
        'main' => 'themes/default/easyui.css',
        'themes/icon.css',
        'themes/color.css',
    ];
    public $theme;
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        parent::init();
        if ($this->theme === false) {
            unset($this->css['main']);
        } elseif ($this->theme !== null) {
            $this->css['main'] = "themes/{$this->theme}/easyui.css";
        }
    }
}
