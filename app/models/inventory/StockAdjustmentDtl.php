<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%stock_adjustment_dtl}}".
 *
 * @property integer $id
 * @property integer $adjustment_id
 * @property integer $item_id
 * @property double $qty
 * @property double $value
 *
 * @property StockAdjustment $adjustment
 */
class StockAdjustmentDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_adjustment_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adjustment_id', 'item_id', 'qty', 'value'], 'required'],
            [['adjustment_id', 'item_id'], 'integer'],
            [['qty', 'value'], 'number'],
            [['adjustment_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockAdjustment::className(), 'targetAttribute' => ['adjustment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'adjustment_id' => 'Adjustment ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdjustment()
    {
        return $this->hasOne(StockAdjustment::className(), ['id' => 'adjustment_id']);
    }
}
