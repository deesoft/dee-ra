<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%stock_opname_dtl}}".
 *
 * @property integer $id
 * @property integer $opname_id
 * @property integer $item_id
 * @property double $qty
 *
 * @property StockOpname $opname
 */
class StockOpnameDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_opname_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opname_id', 'item_id', 'qty'], 'required'],
            [['opname_id', 'item_id'], 'integer'],
            [['qty'], 'number'],
            [['opname_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockOpname::className(), 'targetAttribute' => ['opname_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'opname_id' => 'Opname ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpname()
    {
        return $this->hasOne(StockOpname::className(), ['id' => 'opname_id']);
    }
}
