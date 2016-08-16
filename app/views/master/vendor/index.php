<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\master\Vendor;

/* @var $this yii\web\View */
/* @var $searchModel app\models\master\search\Vendor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vendor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            [
                'attribute' => 'type',
                'value' => 'nmType',
                'filter' => Vendor::enums('TYPE_')
            ],
            'name',
            'contact_name',
            // 'contact_number',
            // 'status',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
