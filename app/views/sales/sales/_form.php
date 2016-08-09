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

$branchs = Branch::options();
?>
<div class="purchase-form">
    <?php
    $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{label} <div class="col-sm-8">{input}</div>',
                'labelOptions' => ['class' => 'col-sm-4 control-label']
            ],
    ]);
    ?>
    <?= $form->errorSummary($model); ?>
    
        <div class="col-lg-4 form-horizontal">
            <?= Html::hiddenInput('', $model->warehouse_id, ['id' => 'init_wh_id']) ?>
            <?= $form->field($model, 'branch_id')->dropDownList($branchs)->label('Branch') ?>
            <?=
                $form->field($model, 'warehouse_id')->dropDownList([], ['prompt' => '-no receive-'])
                ->label('Receive To');
            ?>
            <?= Html::activeHiddenInput($model, 'vendor_id', ['id' => 'vendor_id']) ?>
            <?= $form->field($model, 'vendor_name')->textInput(['id' => 'vendor_name'])->label('Supplier') ?>
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'options' => ['class' => 'form-control', 'style' => 'width:150px;'],
                'dateFormat' => 'php:d-m-Y'
            ])
            ?>            

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <?=
                    $model->isNewRecord ? Html::submitButton('Draft', ['class' => 'btn btn-info',
                            'name' => 'action', 'value' => 'draft']) : ''
                    ?>
                    <?=
                    Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'action',
                        'value' => 'save'])
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <?= $this->render('_detail', ['form' => $form, 'model' => $model]) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
