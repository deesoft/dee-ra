<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\master\Branch;

/* @var $this yii\web\View */
/* @var $model app\models\accounting\Payment */
/* @var $form yii\widgets\ActiveForm */
$branchs = Branch::options();
yii\jui\JuiAsset::register($this);
$this->registerJsFile(yii\helpers\Url::to(['master']));
$this->registerJs($this->render('_form.js'));

?>

<div class="payment-form">
    <?php
    $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{label} <div class="col-sm-8">{input}</div>',
                'labelOptions' => ['class' => 'col-sm-4 control-label']
            ],
    ]);
    ?>

    <div class="row">
        <div class="col-lg-4 form-horizontal">
            <?= $form->field($model, 'branch_id')->dropDownList($branchs)->label('Branch') ?>

            <?= Html::activeHiddenInput($model, 'vendor_id', ['id' => 'vendor_id']) ?>
            <?= $form->field($model, 'vendor_name')->textInput(['id' => 'vendor_name'])->label('Vendor') ?>
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'options' => ['class' => 'form-control', 'style' => 'width:150px;'],
                'dateFormat' => 'php:d-m-Y'
            ])
            ?>

            <?= $form->field($model, 'method')->dropDownList([]) ?>

            <?= $form->field($model, 'value')->textInput() ?>

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?=
                    empty($allowDraft) ? '' : Html::submitButton('Draft', ['class' => 'btn btn-info',
                            'name' => 'action', 'value' => 'draft'])
                    ?>
                    <?=
                    Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'action',
                        'value' => 'save'])
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <?= $this->render('_detail', ['model' => $model, 'form' => $form]) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
