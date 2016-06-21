<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%product_detail}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $code
 * @property string $barcode
 * @property string $name
 * @property string $uom
 * @property integer $isi
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Price[] $prices
 * @property PriceCategory[] $categories
 * @property ProductCode[] $productCodes
 * @property Product $product
 */
class Item extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'code', 'name', 'uom', 'isi'], 'required'],
            [['product_id', 'isi'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['barcode'], 'string', 'max' => 13],
            [['name'], 'string', 'max' => 64],
            [['uom'], 'string', 'max' => 32],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'code' => 'Code',
            'uom' => 'Uom',
            'isi' => 'Isi',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductCodes()
    {
        return $this->hasMany(ProductCode::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            'yii\behaviors\BlameableBehavior',
        ];
    }
}
