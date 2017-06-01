<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php $this->registerJsFile('@web/assets/c1a6917c/jquery.js');?>
<?php $this->registerJsFile('@web/js/city.js');?>
<?php $this->registerJsFile('@web/js/deviceCheck.js');?>
<div class="wrap">
    <?php
    NavBar::begin([

        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            ['label' => 'Orders', 'url' => ['orders/active_orders']],
            ['label' => 'My orders', 'url' => ['orders/my_orders']],
            ['label' => 'About', 'url' => ['delivery/about']],
        ],
    ]);
    if(Yii::$app->user->isGuest){
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Registration', 'url' => ['delivery/registration']],
            ['label' => 'Login', 'url' => ['delivery/login']]
        ]
    ]);
    }

    if(!Yii::$app->user->isGuest):
    ?>

    <div class="navbar-nav navbar-right">

    <div class="dropdown"  style="z-index: 5;">
        <button class="btn btn-success" style="margin:5px;" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <span class="glyphicon glyphicon-user"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dLabel">
            <li>
                <?=Html::a('Profile', Yii::$app->homeUrl.'delivery/my_profile')?>
            </li>
            <li>
                <?=Html::a('Logout ('.Yii::$app->user->identity->login.')', Yii::$app->homeUrl.'delivery/logout')?>
            </li>
            <?php if(isset($ordersReqNum)){ ?>
             <li>
                 Requests <span class="badge"><?php
                     $reqCount = 0;
                     foreach($ordersReqNum as $orderId => $reqNum){
                         $reqCount += $reqNum;
                     }
                     echo $reqCount;
                     ?></span>
             </li>
             <? } ?>
        </ul>
    </div>
    </div>
        <div class="navbar-nav navbar-right">
            <?php

            $form = \yii\bootstrap\ActiveForm::begin([
                'id' => 'cityForm',
                'options' => ['class' => 'form-inline']
            ]);
            ?>
            <label class="text-primary">Your city: </label>
            <select class="form-control cityChangeSelect" name="cityChange" style="margin: 5px;">

                <option value="Kyiv" <? if(Yii::$app->user->identity->city == 'Kyiv') echo 'selected'; ?> >Kyiv</option>
                <option value="Kharkiv"  <? if(Yii::$app->user->identity->city == 'Kharkiv') echo 'selected';  ?>>Kharkiv</option>
                <option value="Lviv"  <? if(Yii::$app->user->identity->city == 'Lviv') echo 'selected';  ?>>Lviv</option>
            </select>
            <?
            \yii\bootstrap\ActiveForm::end();
            ?>
        </div>
    <?
    endif;
    NavBar::end();
    ?>

    <div class="container">

        <?= $content ?>

</div>
    <footer class="container-fluid">

    <?php $session = Yii::$app->session;
    if(isset($session['success'])){
        ?><div class="alert alert-success alert-dismissable pull-left" style="position:fixed;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?= $session->getFlash('success'); ?>
        </div>
    <?

    }
    ?>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
