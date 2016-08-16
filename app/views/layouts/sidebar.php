<?php

use yii\web\View;
use mdm\admin\components\Helper;
use app\widgets\SideNav;

//use yii\helpers\Html;

/* @var $this View */
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php
        echo SideNav::widget([
            'options' => [
                'class' => 'sidebar-menu',
            ],
            'items' => Helper::filter(require '_item_menu.php'),
        ]);
        ?>
    </section>
</aside>