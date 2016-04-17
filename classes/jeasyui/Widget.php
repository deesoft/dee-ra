<?php

namespace classes\jeasyui;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Description of Widget
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Widget extends \yii\base\Widget
{
    public $tag = 'div';
    public $options = [];
    public $clientOptions = [];
    protected $plugin;
    protected $urlOptions = ['url'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if ($this->plugin === null) {
            $ref = new \ReflectionClass($this);
            $this->plugin = strtolower($ref->getShortName());
        }
        ob_start();
        ob_implicit_flush(false);
    }

    protected function getClientOptions()
    {
        return $this->clientOptions;
    }

    protected function registerClientOptions()
    {
        $clientOptions = $this->getClientOptions();
        foreach ($this->urlOptions as $name) {
            if (isset($clientOptions[$name])) {
                $clientOptions[$name] = Url::to($clientOptions[$name]);
            }
        }

        $options = $this->options;
        $view = $this->getView();
        $opts = empty($clientOptions) ? '{}' : Json::htmlEncode($clientOptions);
        EasyuiAsset::register($view);
        $view->registerJs("jQuery('#{$options['id']}').{$this->plugin}($opts);");
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $content = ob_get_clean();
        $this->registerClientOptions();
        echo Html::tag($this->tag, $content, $this->options);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (parent::canGetProperty($name)) {
            return parent::__get($name);
        } else {
            return isset($this->clientOptions[$name]) ? $this->clientOptions[$name] : null;
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (parent::canSetProperty($name)) {
            parent::__set($name, $value);
        } else {
            $this->clientOptions[$name] = $value;
        }
    }
}
