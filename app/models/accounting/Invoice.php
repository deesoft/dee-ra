<?php

namespace app\models\accounting;

use Yii;
use app\models\master\Vendor;
use app\models\master\Branch;

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
 * @property string $nmStatus
 *
 * @property Vendor $vendor
 * @property Branch $branch
 * @property InvoiceDtl[] $items
 * @property PaymentDtl[] $paymentDtls
 * @property InvoicePaid $paid
 */
class Invoice extends \app\classes\ActiveRecord
{
    const TYPE_INCOMING = 1;
    const TYPE_OUTGOING = 2;

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
            [['type', 'branch_id', 'date', 'due_date', 'vendor_id', 'status', 'value'], 'required'],
            [['type', 'branch_id', 'vendor_id', 'reff_type', 'reff_id', 'status'], 'integer'],
            [['!number'], 'autonumber', 'format' => 'formatNumber', 'digit' => 6],
            [['date', 'due_date'], 'safe'],
            [['value', 'tax_value'], 'number'],
            [['number'], 'string', 'max' => 20],
            [['description', 'tax_type'], 'string', 'max' => 64],
        ];
    }

    public function formatNumber()
    {
        $date = date('Ymd');
        return "28{$this->type}.$date.?";
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
    public function getItems()
    {
        return $this->hasMany(InvoiceDtl::className(), ['invoice_id' => 'id']);
    }

    public function setItems($values)
    {
        $this->loadRelated('items', $values);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDtls()
    {
        return $this->hasMany(PaymentDtl::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Vendor::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaid()
    {
        return $this->hasOne(InvoicePaid::className(), ['invoice_id' => 'id']);
    }

    /**
     *
     * @return string
     */
    public function getNmType()
    {
        return $this->getLogical('type', 'TYPE_');
    }
}
