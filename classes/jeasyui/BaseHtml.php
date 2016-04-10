<?php

namespace classes\jeasyui;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Description of BaseHtml
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class BaseHtml extends \yii\helpers\BaseHtml
{
    /**
     * @var \yii\web\View
     */
    private static $_view;

    /**
     * @inheritdoc
     */
    public static function tag($name, $content = '', $options = [])
    {
        if (($easyui = ArrayHelper::remove($options, 'easyui')) !== null) {
            if (is_string($easyui)) {
                $plugin = $easyui;
                $easyui = [];
            } else {
                $plugin = ArrayHelper::remove($easyui, '_plugin');
            }
            /* @var $view \yii\web\View */
            $view = ArrayHelper::remove($easyui, '_view');

            if (empty($options['id'])) {
                parent::addCssClass($options, 'easyui-' . $plugin);
                if (!empty($easyui)) {
                    $options['data-options'] = $easyui;
                }
            } else {
                $easyui = empty($easyui) ? '{}' : Json::htmlEncode($easyui);
                if ($view === null) {
                    if (self::$_view === null) {
                        self::$_view = Yii::$app->controller ? Yii::$app->controller->getView() : Yii::$app->getView();
                    }
                    $view = self::$_view;
                }
                $view->registerJs("jQuery('#{$options['id']}').{$plugin}($easyui);");
            }
            static::registerAsset($view);
        }
        return parent::tag($name, $content, $options);
    }

    /**
     * Register asset
     * @param \yii\web\View $view
     */
    public static function registerAsset($view = null)
    {
        if ($view === null) {
            if (self::$_view === null) {
                self::$_view = Yii::$app->controller ? Yii::$app->controller->getView() : Yii::$app->getView();
            }
            $view = self::$_view;
        }
        EasyuiAsset::register($view);
    }
}
