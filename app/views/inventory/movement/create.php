<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\inventory\GoodsMovement */

$this->title = 'Create Goods Movement';
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
