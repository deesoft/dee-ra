<?php

namespace app\models\inventory;

use Yii;
use app\models\master\Warehouse;
use app\classes\RelatedEvent;
use app\models\accounting\Invoice;
use app\models\master\Cogs;
use app\models\master\ProductStock;
use yii\db\Expression;

/**
 * This is the model class for table "{{%goods_movement}}".
 *
 * @property integer $id
 * @property string $number
 * @property integer $type
 * @property integer $warehouse_id
 * @property string $date
 * @property integer $reff_type
 * @property integer $reff_id
 * @property integer $vendor_id
 * @property string $description
 * @property array $data
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $nmType
 *
 * @property GoodsMovementDtl[] $items
 * @property Warehouse $warehouse
 *
 */
class GoodsMovement extends \app\classes\ActiveRecord
{

    public function init()
    {
        parent::init();
        $this->on('beforeRelatedSave', function($event) {
            /* @var $event RelatedEvent */
            if ($event->relationName === 'items') {
                $event->isValid = $event->item->qty != '' && $event->item->qty != 0;
            }
        });
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_movement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'warehouse_id', 'Date', 'status'], 'required'],
            [['type', 'warehouse_id', 'reff_type', 'reff_id', 'vendor_id', 'status'], 'integer'],
            [['!number'], 'autonumber', 'format' => 'formatNumber', 'digit' => 6],
            [['date', 'data'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function formatNumber()
    {
        $date = date('Ymd');
        return "22{$this->type}.$date.?";
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'warehouse_id' => 'Warehouse ID',
            'date' => 'Date',
            'reff_type' => 'Reff Type',
            'reff_id' => 'Reff ID',
            'vendor_id' => 'Vendor ID',
            'description' => 'Description',
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
    public function getItems()
    {
        return $this->hasMany(GoodsMovementDtl::className(), ['movement_id' => 'id']);
    }

    /**
     * @param array $values
     */
    public function setItems($values)
    {
        $this->loadRelated('items', $values);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

    public function getNmType()
    {
        switch ($this->type) {
            case 1:
                return 'Receive';
            case 2:
                return 'Issue';
            default:
                break;
        }
    }

    /**
     *
     * @return Invoice
     */
    public function createInvoice()
    {
        $model = new Invoice([
            'type' => $this->type === 1 || $this->type === '1' ? 2 : 1,
            'reff_type' => 221,
            'reff_id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'branch_id' => $this->warehouse->branch_id,
            'date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', time() + 30 * 24 * 3600),
            'description' => "Invoice for [{$this->number}]",
            'status' => 10,
        ]);
        $items = [];
        $total = 0;
        foreach ($this->items as $item) {
            $items[] = [
                'item' => $item->item->name,
                'item_id' => $item->item_id,
                'qty' => $item->qty,
                'value' => $item->value,
            ];
            $total += $item->qty * $item->value;
        }
        $model->value = $total;
        $model->items = $items;
        return $model;
    }

    public function applyStock($factor = 1)
    {
        // update stock
        $wh_id = $this->warehouse_id;
        $mv_id = $this->id;
        $factor = $factor * ($this->type == 1 ? 1 : -1);
        $command = Yii::$app->db->createCommand();
        foreach ($this->items as $item) {
            $product_id = $item->item->product_id;
            $qty = $factor * $item->qty * $item->item->volume;
            $ps = ProductStock::findOne(['product_id' => $product_id, 'warehouse_id' => $wh_id]);
            if ($ps) {
                $ps->qty = new Expression('[[qty]] + :added', [':added' => $qty]);
            } else {
                $ps = new ProductStock(['product_id' => $product_id, 'warehouse_id' => $wh_id, 'qty' => $qty]);
            }
            if (!$ps->save(false) || !$ps->refresh() || !$command->insert('{{%product_stock_history}}', [
                    'time' => microtime(true),
                    'warehouse_id' => $wh_id,
                    'product_id' => $product_id,
                    'qty_movement' => $qty,
                    'qty_current' => $ps->qty,
                    'movement_id' => $mv_id,
                ])->execute()) {
                return false;
            }
            if ($item->cogs !== null && $item->cogs !== '' && !$this->updateCogs([
                    'id' => $product_id,
                    'qty' => $qty,
                    'cogs' => $item->cogs,
                ])) {
                return false;
            }
        }
        return true;
    }

    public function updateCogs($params)
    {
        $cogs = Cogs::findOne(['product_id' => $params['id']]);
        if (!$cogs) {
            $cogs = new Cogs([
                'product_id' => $params['id'],
                'cogs' => 0.0
            ]);
        }

        if ($cogs->cogs != $params['cogs']) {
            $current_stock = ProductStock::find()
                ->where(['product_id' => $params['id']])
                ->sum('qty');
            if ($current_stock != 0) {
                $cogs->cogs += ($params['qty'] * ($params['cogs'] - $cogs->cogs)) / $current_stock;
            } else {
                $cogs->cogs = 0;
            }

            return $cogs->save(false);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return[
            [
                'class' => 'mdm\converter\DateConverter',
                'type' => 'date', // 'date', 'time', 'datetime'
                'logicalFormat' => 'php:d-m-Y',
                'attributes' => [
                    'Date' => 'date', // date is original attribute
                ]
            ],
            [
                'class' => 'app\classes\ArrayConverter',
                'attributes' => [
                    'data' => 'extra_data', // extra_data is original attribute
                ]
            ],
            'yii\behaviors\BlameableBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }
}
