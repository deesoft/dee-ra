<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $type
 * @property integer $branch_id
 * @property string $date
 * @property string $due_date
 * @property integer $vendor_id
 * @property integer $reff_type
 * @property integer $reff_id
 * @property integer $status
 * @property string $description
 * @property double $value
 * @property string $tax_type
 * @property double $tax_value
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property InvoiceDtl[] $invoiceDtls
 * @property PaymentDtl[] $paymentDtls
 */
class Invoice extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'type', 'branch_id', 'date', 'due_date', 'vendor_id', 'status', 'value'], 'required'],
            [['type', 'branch_id', 'vendor_id', 'reff_type', 'reff_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date', 'due_date'], 'safe'],
            [['value', 'tax_value'], 'number'],
            [['number'], 'string', 'max' => 20],
            [['description', 'tax_type'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'branch_id' => 'Branch ID',
            'date' => 'Date',
            'due_date' => 'Due Date',
            'vendor_id' => 'Vendor ID',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'status' => 'Status',
            'description' => 'Description',
            'value' => 'Value',
            'tax_type' => 'Tax Type',
            'tax_value' => 'Tax Value',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoiceDtls()
    {
        return $this->hasMany(InvoiceDtl::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDtls()
    {
        return $this->hasMany(PaymentDtl::className(), ['invoice_id' => 'id']);
    }
}
