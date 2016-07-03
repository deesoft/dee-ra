<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $searchModel app\models\inventory\search\GoodsMovement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods Movements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Goods Movement', ['create'], ['class' => 'btn btn-success']) ?>
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
                'filter' => [
                    1 => 'Receive',
                    2 => 'Issue'
                ]
            ],
            [
                'attribute' => 'warehouse_id',
                'value' => 'warehouse.name',
                'label' => 'Warehouse',
                'filter' => Warehouse::options(),
            ],
            'date:date',
            'description:link',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
