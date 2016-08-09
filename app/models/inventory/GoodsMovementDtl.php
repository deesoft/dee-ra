<?php

namespace app\models\inventory;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\master\Item;

/**
 * This is the model class for table "{{%goods_movement_dtl}}".
 *
 * @property integer $id
 * @property integer $movement_id
 * @property integer $item_id
 * @property integer $reff_id
 * @property double $qty
 * @property double $cogs
 * @property double $value
 *
 * @property GoodsMovement $movement
 * @property Item $item
 */
class GoodsMovementDtl extends \app\classes\ActiveRecord
{
    public $extra;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_movement_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'cogs'], 'required'],
            [['qty'], 'required', 'when' => function() {
                return !ArrayHelper::getValue($this->extra, 'qty_null', false);
            }],
            [['movement_id', 'item_id', 'reff_id'], 'integer'],
            [['qty', 'cogs', 'value'], 'number'],
            [['extra'], 'safe']
            //[['movement_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoodsMovement::className(), 'targetAttribute' => ['movement_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'movement_id' => 'Movement ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
            'cogs' => 'Cogs',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovement()
    {
        return $this->hasOne(GoodsMovement::className(), ['id' => 'movement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
