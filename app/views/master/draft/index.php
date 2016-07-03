<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\master\Draft;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\master\search\Draft */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Drafts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="draft-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'type',
                'value' => 'typeName',
                'filter' => Draft::$type_names
            ],
            'description',
            'created_at:date',
            'updated_at:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'urlCreator' => function($action, $model) {
                    switch ($action) {
                        case 'view':
                            $route = Draft::$type_routes[$model->type];
                            if (!is_array($route)) {
                                $route = [$route];
                            }
                            $route['draft_id'] = $model->id;
                            break;

                        case 'delete':
                            $route = ['delete', 'id' => $model->id];
                            break;
                    }
                    return Url::toRoute($route);
                },
                ],
            ],
        ]);
        ?>

</div>
