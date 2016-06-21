<?php

namespace app\models\purchase;

use Yii;
use app\classes\ARCollection;
use app\models\master\Vendor;
use app\models\master\Branch;

/**
 * This is the model class for table "{{%purchase}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $number
 * @property integer $vendor_id
 * @property integer $branch_id
 * @property string $date
 * @property double $value
 * @property double $discount
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PurchaseDtl[]|ARCollection $items
 * @property Vendor $vendor
 * @property Branch $branch
 * 
 */
class Purchase extends \app\classes\ActiveRecord
{
    public $vendor_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%purchase}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_id', 'branch_id', 'Date', 'value', 'status', 'type'], 'required'],
            [['!number'], 'autonumber', 'format' => 'formatNumber', 'digit' => 6],
            [['type', 'vendor_id', 'branch_id', 'status'], 'integer'],
            [['date', 'vendor_name', 'items'], 'safe'],
            [['value', 'discount'], 'number'],
            
        ];
    }

    public function formatNumber()
    {
        $date = date('Ymd');
        return "21{$this->type}.$date.?";
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
            'vendor_id' => 'Vendor ID',
            'branch_id' => 'Branch ID',
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
    public function getItems()
    {
        return $this->hasMany(PurchaseDtl::className(), ['purchase_id' => 'id']);
    }

    public function setItems($items)
    {
        $this->items->setRecords($items);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id'=>'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['id'=>'vendor_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return[
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'Date' => 'date', // date is original attribute
                ]
            ],
            'yii\behaviors\BlameableBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }
}
