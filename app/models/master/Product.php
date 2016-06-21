<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $category_id
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property boolean $stockable
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Cogs $cogs
 * @property Category $category
 * @property ProductGroup $group
 * @property ProductDetail[] $productDetails
 * @property ProductStock[] $productStocks
 * @property Warehouse[] $warehouses
 * @property ProductVendor[] $productVendors
 * @property Vendor[] $vendors
 */
class Product extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'category_id', 'code', 'name', 'status'], 'required'],
            [['group_id', 'category_id', 'status'], 'integer'],
            [['stockable'], 'boolean'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 64],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'category_id' => 'Category ID',
            'name' => 'Name',
            'status' => 'Status',
            'stockable' => 'Stockable',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCogs()
    {
        return $this->hasOne(Cogs::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ProductGroup::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductDetails()
    {
        return $this->hasMany(ProductDetail::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductStocks()
    {
        return $this->hasMany(ProductStock::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])->viaTable('{{%product_stock}}', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductVendors()
    {
        return $this->hasMany(ProductVendor::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendors()
    {
        return $this->hasMany(Vendor::className(), ['id' => 'vendor_id'])->viaTable('{{%product_vendor}}', ['product_id' => 'id']);
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
