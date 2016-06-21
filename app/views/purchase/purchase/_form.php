<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\master\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\purchase\Purchase */
/* @var $form yii\widgets\ActiveForm */
yii\jui\JuiAsset::register($this);
$this->registerJsFile(yii\helpers\Url::to(['master']));
$this->registerJs($this->render('script.js'));
?>
<div class="purchase-form">
    <?php
    $form = ActiveForm::begin([
    ]);
    ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'branch_id')->dropDownList(Branch::options(), ['style' => 'width:50%']) ?>

            <?= Html::activeHiddenInput($model, 'vendor_id', ['id' => 'vendor_id']) ?>
            <?= $form->field($model, 'vendor_name')->textInput(['id' => 'vendor_name']) ?>
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'options' => ['class' => 'form-control', 'style' => 'width:180px;'],
                'dateFormat' => 'php:d-m-Y'
            ])
            ?>

            <?= $form->field($model, 'discount')->textInput(['style' => 'width:100px']) ?>
            <div class="form-group">
                <?= Html::submitButton('Draft', ['class' => 'btn btn-info', 'name' => 'action', 'value' => 'draft']) ?>
                <?=
                Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'action',
                    'value' => 'save'])
                ?>
            </div>
        </div>
        <div class="col-lg-8">
            <?= $this->render('_detail', ['form' => $form, 'model' => $model]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
