<?php

use yii\web\View;
use classes\jeasyui\DataGrid;

//use yii\helpers\Html;

/* @var $this View */
?>
<?= DataGrid::widget([
    'id'=>'dg',
    'columns'=>[
        'number',
        'date',
        'vendor'
    ],
    'clientOptions'=>[
        'url'=>['data'],
        'fit'=>true,
    ]
]) ?>