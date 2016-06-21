<?php
/* @var $this \yii\web\View */
/* @var $content string */

use classes\jeasyui\EasyuiAsset;
use yii\helpers\Html;

EasyuiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="easyui-layout" style="text-align:left">
        <?php $this->beginBody() ?>
        <div id="head-menu">
            <div class="navbar navbar-right">
                <ul>
                    <li><a href="<?= Yii::$app->homeUrl ?>">Home</a></li>
                    <li><a href="/demo/main/index.php">Demo</a></li>
                    <li><a href="/tutorial/index.php">Tutorial</a></li>
                    <li><a href="/documentation/index.php">Documentation</a></li>
                    <li><a href="/download/index.php">Download</a></li>
                    <li><a href="/extension/index.php">Extension</a></li>
                    <li><a href="/contact.php">Contact</a></li>
                    <li><a href="/forum/index.php">Forum</a></li>
                </ul>
            </div>
        </div>
        <div region="north" border="false" class="group wrap header" style="height:66px;font-size:100%">

        </div>
        <div region="west" split="true" title="Plugins" style="width:20%;min-width:180px;padding:5px;">
            <ul class="easyui-tree">
            </ul>
        </div>
        <div region="center">
            <div id="content" class="easyui-panel" fit="true" border="false" plain="true">
                <?= $content ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
