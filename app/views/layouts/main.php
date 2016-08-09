<?php

use yii\helpers\Html;
use app\assets\AdminLteAsset;
use app\widgets\SideNav;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AdminLteAsset::register($this);
$this->registerJs($this->render('adminlte.js'));
$this->registerCss($this->render('easyui.css'));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body class="skin-red sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <?= $this->render('header'); ?>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <?php
                    echo SideNav::widget([
                        'options' => [
                            'class' => 'sidebar-menu',
                        ],
                        'items' => [],
                    ]);
                    ?>
                </section>
            </aside>
            <div class="content-wrapper" >
                <section class="content-header">
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => ['label' => Yii::t('yii', 'Home'), 'url' => Yii::$app->homeUrl],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                </section>
                <section class="content">
                    <?= $content; ?>
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    Version 2.0
                </div>
                <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Deesoft</a>.</strong>
                All rights reserved.
            </footer>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
