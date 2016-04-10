<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "invoice_dtl".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $item_type
 * @property integer $item_id
 * @property double $qty
 * @property double $item_value
 * @property string $tax_type
 * @property double $tax_value
 *
 * @property Invoice $invoice
 */
class InvoiceDtl extends \yii\db\ActiveRecord
{
    const TYPE_PRODUCT = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qty', 'item_value'], 'required'],
            [['invoice_id', 'item_type', 'item_id'], 'integer'],
            [['qty', 'item_value', 'tax_value'], 'number'],
            [['tax_type'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'item_type' => 'Item Type',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'item_value' => 'Item Value',
            'tax_type' => 'Tax Type',
            'tax_value' => 'Tax Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(\app\models\master\Product::className(), ['id' => 'item_id']);
    }
}
