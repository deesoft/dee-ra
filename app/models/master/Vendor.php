<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%vendor}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $code
 * @property string $name
 * @property string $contact_name
 * @property string $contact_number
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ProductVendor[] $productVendors
 * @property Product[] $products
 * @property VendorDetail $vendorDetail
 */
class Vendor extends \app\classes\ActiveRecord
{
    const TYPE_SUPPLIER = 1;
    const TYPE_CUSTOMER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%vendor}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'code', 'name', 'status'], 'required'],
            [['type', 'status'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name', 'contact_name', 'contact_number'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'code' => 'Code',
            'name' => 'Name',
            'contact_name' => 'Contact Name',
            'contact_number' => 'Contact Number',
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
    public function getProductVendors()
    {
        return $this->hasMany(ProductVendor::className(), ['vendor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%product_vendor}}', ['vendor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendorDetail()
    {
        return $this->hasOne(VendorDetail::className(), ['id' => 'id']);
    }

    public function getNmType()
    {
        return $this->getLogical('type', 'TYPE_');
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
