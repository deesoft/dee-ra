<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use app\models\purchase\Purchase;
use app\models\purchase\PurchaseDtl;
use yii\helpers\Html;

/* @var $this View */
/* @var $model Payment */
?>
<div style="display: none;">
    <?= Html::activeHiddenInput($model, 'value')?>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th style="width: 40px">#</th>
            <th>
                Invoice Number
            </th>
            <th class="items" style="width: 15%">
                Value
            </th>
            <th class="items" style="width: 15%">
                Price
            </th>
            <th style="width: 15%">
                Total Line
            </th>
        </tr>
        <tr>
            <td colspan="3">
                <div class="input-group" style="width:100%;">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="input-product" class="form-control" placeholder='Search Product..'>
                </div>
            </td>
            <td>&nbsp;</td>
            <th><span class="pull-right" id="total"><?= $model->value ?></span></th>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => PurchaseDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ],
        'viewParams' => [
            'model' => $model,
        ]
    ])
    ?>
</table>
