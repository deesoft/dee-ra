<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $type
 * @property integer $branch_id
 * @property integer $vendor_id
 * @property string $date
 * @property string $method
 * @property integer $coa_id
 * @property double $value
 * @property integer $potongan_coa_id
 * @property double $potongan
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Coa $coa
 * @property Coa $potonganCoa
 * @property PaymentDtl[] $paymentDtls
 */
class Payment extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'branch_id', 'vendor_id', 'date', 'method', 'coa_id', 'value', 'status'], 'required'],
            [['type', 'branch_id', 'vendor_id', 'coa_id', 'potongan_coa_id', 'status'], 'integer'],
            [['date'], 'safe'],
            [['value', 'potongan'], 'number'],
            [['!number'], 'autonumber', 'format' => 'formatNumber', 'digit' => '6'],
            [['method'], 'string', 'max' => 32],
            [['coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['coa_id' => 'id']],
            [['potongan_coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['potongan_coa_id' => 'id']],
        ];
    }

    public function formatNumber()
    {
        $date = date('Ymd');
        return "27{$this->type}.$date.?";
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
            'vendor_id' => 'Vendor ID',
            'date' => 'Date',
            'method' => 'Method',
            'coa_id' => 'Coa ID',
            'value' => 'Value',
            'potongan_coa_id' => 'Potongan Coa ID',
            'potongan' => 'Potongan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoa()
    {
        return $this->hasOne(Coa::className(), ['id' => 'coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPotonganCoa()
    {
        return $this->hasOne(Coa::className(), ['id' => 'potongan_coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDtls()
    {
        return $this->hasMany(PaymentDtl::className(), ['payment_id' => 'id']);
    }
}
