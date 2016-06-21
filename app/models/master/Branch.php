<?php

namespace app\models\master;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%branch}}".
 *
 * @property integer $id
 * @property integer $orgn_id
 * @property string $code
 * @property string $name
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Orgn $orgn
 * @property UserToBranch[] $userToBranches
 * @property Warehouse[] $warehouses
 */
class Branch extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%branch}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orgn_id', 'code', 'name'], 'required'],
            [['orgn_id'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 64],
            [['orgn_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orgn::className(),
                'targetAttribute' => ['orgn_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orgn_id' => 'Orgn ID',
            'code' => 'Code',
            'name' => 'Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgn()
    {
        return $this->hasOne(Orgn::className(), ['id' => 'orgn_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserToBranches()
    {
        return $this->hasMany(UserToBranch::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouses()
    {
        return $this->hasMany(Warehouse::className(), ['branch_id' => 'id']);
    }

    public static function options($user_id = null)
    {
        $query = static::find()->asArray();
        if ($user_id !== null) {
            $queryId = (new \yii\db\Query())
                ->select('branch_id')
                ->from('{{%user_to_branch]]')
                ->where(['user_id' => $user_id]);
            $query->andWhere(['id' => $queryId]);
        }
        return ArrayHelper::map($query->all(), 'id', 'name');
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
