<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%product_code}}".
 *
 * @property string $barcode
 * @property integer $item_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property ProductDetail $item
 */
class ProductCode extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcode', 'item_id'], 'required'],
            [['item_id'], 'integer'],
            [['barcode'], 'string', 'max' => 13],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductDetail::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'barcode' => 'Barcode',
            'item_id' => 'Item ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
