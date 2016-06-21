<?php

namespace app\models\inventory;

use Yii;
use app\classes\ARCollection;

/**
 * This is the model class for table "{{%goods_movement}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $type
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $reff_type
 * @property integer $reff_id
 * @property integer $vendor_id
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property GoodsMovementDtl[]|ARCollection $items
 */
class GoodsMovement extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_movement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'warehouse_id', 'Date', 'status'], 'required'],
            [['type', 'warehouse_id', 'reff_type', 'reff_id', 'vendor_id', 'status'], 'integer'],
            [['!number'], 'autonumber', 'format' => 'autonumber', 'digit' => 6],
            [['date'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function autonumber()
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
            'number' => 'Number',
            'type' => 'Type',
            'warehouse_id' => 'Warehouse ID',
            'date' => 'Date',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'vendor_id' => 'Vendor ID',
            'description' => 'Description',
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
        return $this->hasMany(GoodsMovementDtl::className(), ['movement_id' => 'id']);
    }

    public function setItems($items)
    {
        $this->items->setRecords($items);
    }
}
