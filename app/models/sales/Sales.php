<?php

namespace app\models\sales;

use Yii;

/**
 * This is the model class for table "{{%sales}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $number
 * @property integer $branch_id
 * @property integer $vendor_id
 * @property string $date
 * @property double $value
 * @property double $discount
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property SalesDtl[] $salesDtls
 */
class Sales extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sales}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'number', 'branch_id', 'date', 'value', 'status'], 'required'],
            [['type', 'branch_id', 'vendor_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['value', 'discount'], 'number'],
            [['number'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'number' => 'Number',
            'branch_id' => 'Branch ID',
            'vendor_id' => 'Vendor ID',
            'date' => 'Date',
            'value' => 'Value',
            'discount' => 'Discount',
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
    public function getSalesDtls()
    {
        return $this->hasMany(SalesDtl::className(), ['sales_id' => 'id']);
    }
}
