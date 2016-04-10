<?php

namespace app\models\inventory;

use Yii;
use app\models\master\search\Product;

/**
 * This is the model class for table "{{%stock_opname_dtl}}".
 *
 * @property integer $opname_id
 * @property integer $product_id
 * @property integer $uom_id
 * @property double $qty
 *
 * @property StockOpname $opname
 */
class StockOpnameDtl extends \yii\db\ActiveRecord
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
            [['opname_id', 'product_id', 'uom_id', 'qty'], 'required'],
            [['opname_id', 'product_id', 'uom_id'], 'integer'],
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
            'opname_id' => 'Opname ID',
            'product_id' => 'Product ID',
            'uom_id' => 'Uom ID',
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

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
