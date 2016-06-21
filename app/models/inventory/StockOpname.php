<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%stock_opname}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $status
 * @property string $description
 * @property string $operator
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property StockOpnameDtl[] $stockOpnameDtls
 */
class StockOpname extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_opname}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'warehouse_id', 'date', 'status'], 'required'],
            [['warehouse_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['number'], 'string', 'max' => 20],
            [['description', 'operator'], 'string', 'max' => 255],
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
            'warehouse_id' => 'Warehouse ID',
            'date' => 'Date',
            'status' => 'Status',
            'description' => 'Description',
            'operator' => 'Operator',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStockOpnameDtls()
    {
        return $this->hasMany(StockOpnameDtl::className(), ['opname_id' => 'id']);
    }
}
