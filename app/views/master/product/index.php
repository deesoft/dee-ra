<?php

use yii\web\View;
use classes\jeasyui\DataGrid;
use classes\jeasyui\Dialog;

//use yii\helpers\Html;

/* @var $this View */
$this->title = 'Product';

$this->registerJs($this->render('_script.js'));
?>
<?=
DataGrid::widget([
    'id' => 'dg',
    'columns' => [
        ['field' => 'code', 'title' => 'Code', 'sortable' => true],
        ['field' => 'name', 'title' => 'Name', 'sortable' => true],
        ['field' => 'category.name', 'title' => 'Category', 'sortable' => true],
        'nmStatus'
    ],
    'clientOptions' => [
        'url' => ['data'],
        'fit' => true,
        'pagination' => true,
        //'multiSort' => true,
        'rownumbers' => true,
        'pageSize' => 20,
        'toolbar' => '#dg-toolbar',
        'mode'=>'remote'
    ]
])
?>
<div id="dg-toolbar">
    <a id="btn-create" class="easyui-linkbutton" flat="true">Create</a>
    <input id="srch">
    <input id="vbox" class="easyui-textbox">
    <a id="btn-vbox" class="easyui-linkbutton">Klik</a>
</div>
