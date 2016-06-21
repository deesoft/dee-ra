<?php

namespace app\models\accounting;

use Yii;

/**
 * This is the model class for table "{{%entri_sheet}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $d_coa_id
 * @property integer $k_coa_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Coa $dCoa
 * @property Coa $kCoa
 */
class EntriSheet extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entri_sheet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'd_coa_id', 'k_coa_id'], 'required'],
            [['d_coa_id', 'k_coa_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128],
            [['d_coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['d_coa_id' => 'id']],
            [['k_coa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Coa::className(), 'targetAttribute' => ['k_coa_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'd_coa_id' => 'D Coa ID',
            'k_coa_id' => 'K Coa ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDCoa()
    {
        return $this->hasOne(Coa::className(), ['id' => 'd_coa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKCoa()
    {
        return $this->hasOne(Coa::className(), ['id' => 'k_coa_id']);
    }
}
