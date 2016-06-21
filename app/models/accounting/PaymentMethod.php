<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%payment_method}}".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $method
 * @property integer $coa_id
 * @property double $potongan
 * @property integer $potongan_coa_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class PaymentMethod extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_method}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'method', 'coa_id'], 'required'],
            [['branch_id', 'coa_id', 'potongan_coa_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['potongan'], 'number'],
            [['method'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch ID',
            'method' => 'Method',
            'coa_id' => 'Coa ID',
            'potongan' => 'Potongan',
            'potongan_coa_id' => 'Potongan Coa ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
