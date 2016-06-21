<?php

namespace app\models\sales;

use Yii;

/**
 * This is the model class for table "{{%sales_dtl}}".
 *
 * @property integer $id
 * @property integer $sales_id
 * @property integer $item_id
 * @property double $qty
 * @property double $price
 * @property double $cogs
 * @property double $discount
 * @property double $tax
 * @property string $extra
 *
 * @property Sales $sales
 */
class SalesDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_id', 'item_id', 'qty', 'price', 'cogs'], 'required'],
            [['sales_id', 'item_id'], 'integer'],
            [['qty', 'price', 'cogs', 'discount', 'tax'], 'number'],
            [['extra'], 'string', 'max' => 255],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['sales_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sales_id' => 'Sales ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'cogs' => 'Cogs',
            'discount' => 'Discount',
            'tax' => 'Tax',
            'extra' => 'Extra',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(Sales::className(), ['id' => 'sales_id']);
    }
}
