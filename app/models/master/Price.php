<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%price}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $category_id
 * @property double $price
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property PriceCategory $category
 * @property ProductDetail $item
 */
class Price extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'category_id', 'price'], 'required'],
            [['item_id', 'category_id'], 'integer'],
            [['price'], 'number'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductDetail::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PriceCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(ProductDetail::className(), ['id' => 'item_id']);
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
