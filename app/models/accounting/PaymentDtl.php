<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%payment_dtl}}".
 *
 * @property integer $id
 * @property integer $payment_id
 * @property integer $invoice_id
 * @property double $value
 *
 * @property Invoice $invoice
 * @property Payment $payment
 */
class PaymentDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id', 'invoice_id', 'value'], 'required'],
            [['payment_id', 'invoice_id'], 'integer'],
            [['value'], 'number'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_id' => 'Payment ID',
            'invoice_id' => 'Invoice ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
}
