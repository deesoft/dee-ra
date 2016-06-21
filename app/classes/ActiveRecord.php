<?php

namespace app\classes;

use Yii;
/**
 * Description of ActiveRecord
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
//    public function formName()
//    {
//        return '';
//    }

    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }
}
