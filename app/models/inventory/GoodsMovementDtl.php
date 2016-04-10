<?php

namespace app\models\inventory;

use Yii;
use app\models\master\Product;
use app\models\master\Uom;
use app\models\master\ProductUom;

/**
 * This is the model class for table "goods_movement_dtl".
 *
 * @property integer $movement_id
 * @property integer $product_id
 * @property integer $uom_id
 * @property double $qty
 * @property double $value
 * @property double $cogs
 *
 * @property GoodsMovement $movement
 * @property Product $product
 */
class GoodsMovementDtl extends \yii\db\ActiveRecord
{
    public $sisa;

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
            [['product_id', 'uom_id',], 'required'],
            [['movement_id', 'product_id', 'uom_id'], 'integer'],
            [['qty', 'value', 'cogs', 'sisa'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'movement_id' => 'Movement ID',
            'product_id' => 'Product ID',
            'uom_id' => 'Uom ID',
            'qty' => 'Qty',
            'value' => 'Item Value',
            'cogs' => 'Cogs',
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUom()
    {
        return $this->hasOne(Uom::className(), ['id' => 'uom_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductUom()
    {
        return $this->hasOne(ProductUom::className(), ['product_id' => 'product_id', 'uom_id' => 'uom_id']);
    }
}
