<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%product_stock_history}}".
 *
 * @property integer $id
 * @property integer $warehouse_id
 * @property integer $product_id
 * @property double $qty_movement
 * @property double $qty_current
 * @property integer $movement_id
 */
class ProductStockHistory extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_stock_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_id', 'product_id', 'qty_movement', 'qty_current'], 'required'],
            [['warehouse_id', 'product_id', 'movement_id'], 'integer'],
            [['qty_movement', 'qty_current'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'warehouse_id' => 'Warehouse ID',
            'product_id' => 'Product ID',
            'qty_movement' => 'Qty Movement',
            'qty_current' => 'Qty Current',
            'movement_id' => 'Movement ID',
        ];
    }
}
