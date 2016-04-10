<?php
use app\models\inventory\GoodsMovement;
return [
    // purchase
    10 => [
        'name' => 'Purchase',
        'class' => 'app\models\purchase\Purchase',
        'action' => '/purchase/purchase/view',
        'type' => GoodsMovement::TYPE_RECEIVE,
        'onlyStatus' => 20,
        'vendor'=>'vendor_id',
        'items' => 'generateReceive',
    ],
    // Movement
    20 => [
        'name' => 'Issue',
        'class' => 'app\models\inventory\GoodsMovement',
        'action' => '/inventory/gm-manual/view',
        'type' => GoodsMovement::TYPE_RECEIVE,
        'onlyStatus' => 20,
        'items' => 'generateReceiveFromIssueTransfer',
    ],
    // transfer
    30 => [
        'name' => 'Transfer',
        'class' => 'app\models\inventory\Transfer',
        'action' => '/inventory/transfer/view',
        'type' => GoodsMovement::TYPE_ISSUE,
        'onlyStatus' => 20,
        'items' => 'generateReceive',
    ],
    // sales
    60 => [
        'name' => 'Sales',
        'class' => 'app\models\sales\Sales',
        'action' => '/sales/sales/view',
        'type' => 20,
        'onlyStatus' => 20,
        'items' => 'items',
        'itemField' => [
            'product_id' => 'product_id',
            'uom_id' => 'uom_id',
            'value' => 'price',
            'cogs' => 'cogs'
        ],
    ],
];
