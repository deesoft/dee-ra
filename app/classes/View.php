<?php

namespace app\classes;

/**
 * Description of View
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class View extends \yii\web\View
{
    public $localVars = [];
    public function renderFile($viewFile, $params = [], $context = null)
    {
        if(!empty($this->localVars)){
            $params = array_merge($this->localVars, $params);
        }
        parent::renderFile($viewFile, $params, $context);
    }
}
