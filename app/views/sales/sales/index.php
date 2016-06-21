<?php

use yii\web\View;
use yii\helpers\Url;
//use yii\helpers\Html;

/* @var $this View */
classes\jeasyui\EasyuiAsset::register($this);
$opts = [
    'itemUrl'=>  Url::to(['master-item'])
];
$this->registerJs('var dOpts = '.  json_encode($opts).';');
$this->registerJs($this->render('script.js'));
?>
<div id="main-tabs" class="easyui-tabs" data-options="fit:true,showHeader:false,border:false,selected:1">
    <div>
        <table id="main-datagrid" fit="true" class="easyui-datagrid">
            <thead>
                <tr>
                    <th data-options="field:'itemid',width:80">Item ID</th>
                    <th data-options="field:'productid',width:100">Product</th>
                    <th data-options="field:'listprice',width:80,align:'right'">List Price</th>
                    <th data-options="field:'unitcost',width:80,align:'right'">Unit Cost</th>
                    <th data-options="field:'attr1',width:250">Attribute</th>
                    <th data-options="field:'status',width:60,align:'center'">Status</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="">
        <?= $this->render('form'); ?>
    </div>
</div>
