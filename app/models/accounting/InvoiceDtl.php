<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%invoice_dtl}}".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $item_id
 * @property string $item
 * @property double $qty
 * @property double $value
 *
 * @property Invoice $invoice
 */
class InvoiceDtl extends \app\classes\ActiveRecord
{
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
            [['invoice_id', 'item', 'value'], 'required'],
            [['invoice_id', 'item_id'], 'integer'],
            [['qty', 'value'], 'number'],
            [['item'], 'string', 'max' => 64],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
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
            'item_id' => 'Item ID',
            'item' => 'Item',
            'qty' => 'Qty',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }
}
