<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\purchase\Purchase */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <div class="row">
        <div class="col-lg-4">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'number',
                    'vendor.name',
                    'branch.name',
                    'date:date',
                    'value',
                    'discount',
                ],
            ])
            ?>
        </div>

        <div class="nav-tabs-justified col-lg-8"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
            </ul>
            <div class="tab-content" >
                <div class="tab-pane active" id="item">
                    <?=
                    GridView::widget([
                        'dataProvider' => new yii\data\ActiveDataProvider([
                            'query' => $model->getItems()->with(['item']),
                            'pagination' => false,
                            'sort' => false,
                            ]),
                        'tableOptions' => ['class' => 'table table-hover'],
                        'layout' => '{items}',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'item.code',
                                'header' => 'Code'
                            ],
                            [
                                'attribute' => 'item.name',
                                'header' => 'Product Name'
                            ],
                            [
                                'attribute' => 'price',
                                'header' => 'Price'
                            ],
                            'qty',
                        ]
                    ])
                    ?>
                </div>
                <div class="tab-pane" id="notes">

                </div>
            </div>
        </div>
    </div>
</div>
