<?php

namespace app\models\purchase;

use Yii;
use app\models\master\Item;
/**
 * This is the model class for table "{{%purchase_dtl}}".
 *
 * @property integer $id
 * @property integer $purchase_id
 * @property integer $item_id
 * @property double $qty
 * @property double $price
 * @property double $discount
 * @property string $extra
 *
 * @property Purchase $purchase
 */
class PurchaseDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%purchase_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'qty', 'price'], 'required'],
            [['purchase_id', 'item_id'], 'integer'],
            [['qty', 'price', 'discount'], 'number'],
            [['extra'], 'string', 'max' => 255],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_id' => 'Purchase ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'discount' => 'Discount',
            'extra' => 'Extra',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
