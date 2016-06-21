<?php

namespace app\classes;

use Yii;

/**
 * Description of ActiveQuery
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ActiveQuery extends \yii\db\ActiveQuery
{

    public function findFor($name, $model)
    {
        $related = parent::findFor($name, $model);
        if ($this->multiple && empty($this->via)) {
            return Yii::createObject([
                    'class' => ARCollection::className(),
                    'link' => $this->link,
                    'model' => $model,
                    'modelClass' => $this->modelClass,
                    'name' => $name,
            ], [$related]);
        }
        return $related;
    }
}
