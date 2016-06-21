<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\purchase\search\Purchase */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Draft';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Purchase', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'description',
            'created_at:date:Date',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function($action, $model) {
                    switch ($action) {
                        case 'view':
                            return yii\helpers\Url::toRoute(['create', 'draft_id' => $model->id]);
                        case 'delete':
                            return yii\helpers\Url::toRoute(['delete-draft', 'id' => $model->id]);
                    }
                },
                    'template' => '{view} {delete}',
                ],
            ],
        ]);
        ?>

</div>
