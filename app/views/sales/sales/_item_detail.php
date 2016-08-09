<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
    <?=
    Html::activeHiddenInput($model, "items[$key][item_id]", ['data-field' => 'item_id', 'id' => false])
    ?>
</td>
<td>
    <span data-field="item"><?= Html::getAttributeValue($model, "items[$key][item][name]") ?></span>
</td>
<td>
    <?=
    Html::activeTextInput($model, "items[$key][price]", ['class' => 'form-control',
        'data-field' => 'price', 'size' => 5, 'id' => false, 'required' => true])
    ?>
</td>
<td>
    <?=
    Html::activeTextInput($model, "items[$key][qty]", ['class' => 'form-control',
        'data-field' => 'qty', 'size' => 5, 'id' => false, 'required' => true])
    ?>
</td>
<td style="text-align: right;">
    <span data-field="totalLine"><?=
    (Html::getAttributeValue($model, "items[$key][price]")
        * Html::getAttributeValue($model, "items[$key][qty]")
        * 1) ?></span>
</td>