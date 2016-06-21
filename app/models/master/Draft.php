<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "{{%draft}}".
 *
 * @property integer $id
 * @property integer $type
 * @property resource $data
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property array $value
 * @property string $description
 */
class Draft extends \app\classes\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%draft}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'integer'],
            [['data', 'description'], 'string'],
        ];
    }

    public function getValue()
    {
        if ($this->data) {
            if (is_resource($this->data) && get_resource_type($this->data) === 'stream') {
                return unserialize(stream_get_contents($this->data));
            } else {
                return unserialize($this->data);
            }
        }
    }

    public function setValue($value)
    {
        $this->data = $value === null ? null : serialize($value);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'data' => 'Data',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
