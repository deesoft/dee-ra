<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\accounting\Invoice;

/* @var $this yii\web\View */
/* @var $searchModel app\models\accounting\search\Invoice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'number',
            [
                'attribute' => 'type',
                'value' => 'nmType',
                'filter' => Invoice::enums('TYPE_'),
            ],
            [
                'attribute' => 'vendor_id',
                'value' => 'vendor.name',
                'label' => 'Vendor',
            ],
            'due_date',
            'description:link',
            'value',
            'paid.value',
            // 'due_date',
            // 'vendor_id',
            // 'reff_type',
            // 'reff_id',
            // 'status',
            // 'description',
            // 'value',
            // 'tax_type',
            // 'tax_value',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
