<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%goods_movement_dtl}}".
 *
 * @property integer $id
 * @property integer $movement_id
 * @property integer $item_id
 * @property double $qty
 * @property double $cogs
 * @property double $value
 *
 * @property GoodsMovement $movement
 */
class GoodsMovementDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_movement_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['movement_id', 'item_id', 'qty', 'cogs'], 'required'],
            [['movement_id', 'item_id'], 'integer'],
            [['qty', 'cogs', 'value'], 'number'],
            [['movement_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsMovement::className(), 'targetAttribute' => ['movement_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'movement_id' => 'Movement ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'cogs' => 'Cogs',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovement()
    {
        return $this->hasOne(GoodsMovement::className(), ['id' => 'movement_id']);
    }
}
