<?php

namespace app\models\purchase;

use Yii;
use app\models\master\Vendor;
use app\models\master\Branch;
use app\models\inventory\GoodsMovement;
use app\models\accounting\GlHeader;
use yii\db\Query;

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
 * @property PurchaseDtl[] $items
 * @property Vendor $vendor
 * @property Branch $branch
 * @property GoodsMovement[] $movements
 * @property GlHeader $gl
 *
 * @property boolean $received
 * @property boolean $posted
 * 
 */
class Purchase extends \app\classes\ActiveRecord
{
    public $vendor_name;
    public $warehouse_id;

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
            [['type', 'vendor_id', 'branch_id', 'status', 'warehouse_id'], 'integer'],
            [['date', 'vendor_name', 'items'], 'safe'],
            [['value', 'discount'], 'number'],
            [['items'], 'checkItems', 'skipOnEmpty' => false],
        ];
    }

    public function formatNumber()
    {
        $date = date('Ymd');
        return "21{$this->type}.$date.?";
    }

    public function checkItems()
    {
        if (count($this->items) == 0) {
            $this->addError('items', 'Items cannot empty');
        }
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

    /**
     * @param array $values
     */
    public function setItems($values)
    {
        $this->loadRelated('items', $values);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
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
    public function getMovements()
    {
        return $this->hasMany(GoodsMovement::className(), ['reff_id' => 'id'])
                ->onCondition(['{{%goods_movement}}.reff_type' => 211]);
    }

    /**
     * @return boolean true when purcahse has received
     */
    public function getReceived()
    {
        return count($this->movements) > 0;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGl()
    {
        return $this->hasOne(GlHeader::className(), ['reff_id' => 'id'])
                ->onCondition(['{{%gl_header}}.reff_type' => 211]);
    }

    /**
     * @return boolean true when purcahse has received
     */
    public function getPosted()
    {
        return $this->gl !== null;
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
