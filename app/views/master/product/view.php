<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\master\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-4">
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
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'code',
                    'group.name:text:Group',
                    'category.name:text:Category',
                    'name',
                ],
            ])
            ?>
        </div>
        <div class="col-lg-8">
            <div class="nav-tabs-justified">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#unit" data-toggle="tab" aria-expanded="false">Unit</a></li>
                    <li><a href="#price" data-toggle="tab" aria-expanded="false">Price</a></li>
                </ul>
                <div class="tab-content" >
                    <div class="tab-pane active" id="unit">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th width="100px">Unit</th>
                                    <th width="70px">Volume</th>
                                    <th  width="40px">&nbsp;</th>
                                </tr>
                                <tr>
                                    <td><input class="form-control"></td>
                                    <td><input class="form-control"></td>
                                    <td><input class="form-control"></td>
                                    <td><a><i class="fa fa-plus"></i></a></td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="price">
                        Price
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
