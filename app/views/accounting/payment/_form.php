<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\accounting\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{label} <div class="col-sm-8">{input}</div>',
                'labelOptions' => ['class' => 'col-sm-4 control-label']
            ],
    ]); ?>

    <div class="row">
        <div class="col-lg-4 form-horizontal">
            <?= $form->field($model, 'branch_id')->textInput() ?>

            <?= $form->field($model, 'vendor_id')->textInput() ?>

            <?= $form->field($model, 'date')->textInput() ?>

            <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'coa_id')->textInput() ?>

            <?= $form->field($model, 'value')->textInput() ?>

            <?= $form->field($model, 'potongan_coa_id')->textInput() ?>

            <?= $form->field($model, 'potongan')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord
                            ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-lg-8">
            <?= $this->render('_detail', ['model'=>$model,'form'=>$form])?>
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
