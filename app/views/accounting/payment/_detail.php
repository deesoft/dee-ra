<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use app\models\accounting\Payment;
use app\models\accounting\PaymentDtl;
use mdm\widgets\GridInput;
use yii\helpers\Html;

/* @var $this View */
/* @var $model Payment */

$header = <<<HTML
<tr>
    <th style="width: 40px">#</th>
    <th>
        Invoice Number
    </th>
    <th class="items" style="width: 15%">
        Total
    </th>
    <th class="items" style="width: 15%">
        Paid
    </th>
    <th style="width: 15%">
        Value
    </th>
</tr>
<tr>
    <td colspan="3">
        <div class="input-group" style="width:100%;">
            <span class="input-group-addon">
                <i class="fa fa-search"></i>
            </span>
            <input id="input-invoice" class="form-control" placeholder='Search Invoice..'>
        </div>
    </td>
    <td>&nbsp;</td>
    <th><span class="pull-right" id="total">{$model->value}</span></th>
</tr>
HTML;
?>

<?=
GridInput::widget([
    'columns' => [
        ['class' => 'mdm\widgets\ButtonColumn'],
        [
            'value' => 'invoice.number'
        ],
        [
            'value' => 'invoice.value',
        ],
        'value'
    ],
    'header' => $header,
    'id' => 'detail-grid',
    'allModels' => $model->items,
    'model' => PaymentDtl::className(),
]);
?>
