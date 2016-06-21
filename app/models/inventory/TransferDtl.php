<?php

namespace app\models\inventory;

use Yii;

/**
 * This is the model class for table "{{%transfer_dtl}}".
 *
 * @property integer $id
 * @property integer $transfer_id
 * @property integer $item_id
 * @property double $qty
 *
 * @property Transfer $transfer
 */
class TransferDtl extends \app\classes\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transfer_dtl}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transfer_id', 'item_id'], 'required'],
            [['transfer_id', 'item_id'], 'integer'],
            [['qty'], 'number'],
            [['transfer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transfer::className(), 'targetAttribute' => ['transfer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transfer_id' => 'Transfer ID',
            'item_id' => 'Item ID',
            'qty' => 'Qty',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfer()
    {
        return $this->hasOne(Transfer::className(), ['id' => 'transfer_id']);
    }
}
