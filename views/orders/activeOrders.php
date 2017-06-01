<?php

use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\i18n\Formatter;

$this->title = "Orders";
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/assets/c1a6917c/jquery.js');
$this->registerJsFile('@web/js/activeOrders.js');
?>

<div class="container-fluid">
    <div class="row">
    <div class="col-lg-3">
        <?php if(isset($orders[0])){ ?>
       <div class="mt-5" style="margin: 20px 20px 30px auto;"><?= Html::a('Add order', ['orders/add_order'],
               ['class' => 'btn btn-primary btn-block' , 'style' => 'margin:80px 5px 5px 10px;']);?></div>

            <div class="filters">
                <h2 class="text-center"><small>Filters</small></h2>

                    <ul class="list-group">
                        <li class="list-group-item" data-toggle="collapse" data-target="#dateCol">Date</li>
                        <div id="dateCol" class="collapse">
                           <input type="date" id="dateField" class="filterField" style="width:100%;" value="<?= $filters['date']; ?>">
                        </div>
                        <li class="list-group-item" data-toggle="collapse" data-target="#timeCol">Time start</li>
                        <div id="timeCol" class="collapse text-center">
                            <input type="text" class="filterField" style="width:40px;" value="<?= explode(':', $filters['time'])[0]; ?>" id="timeHHField" >
                            :
                            <input type="text" class="filterField" style="width:40px;" value="<?= explode(':', $filters['time'])[1]; ?>" id="timeMMField" >
                        </div>
                        <li class="list-group-item" data-toggle="collapse" data-target="#priceCol">Minimum price</li>
                        <div id="priceCol" class="collapse">
                            <input type="text" class="filterField" id="priceField" value="<?= $filters['price'] ?>" style="width:100%;">
                        </div>
                    </ul>
                <input type="text" id="searchField" value="<?= $filters['search'] ?>" placeholder="Search">
                <p class="text-center"><input type="button" id="filterSubmitButton" class="btn btn-success" value="Search"></p>

    </div>
    <?}?>
    </div>

    <div class="col-lg-7">
        <h2 class="text-center text-muted">Orders</h2>
        <div class="row">

            <?php if(isset($orders[0])){ ?>
        <div class="pull-right" style="width:100px;">
        <select class="form-control sortMethodSelect" name="sortMethodSelect" style="margin: 5px;">

            <option value="new" <?= $filters['orderBy']=='new' ? 'selected' : null ?>>New</option>
            <option value="hot" <?= $filters['orderBy']=='hot' ? 'selected' : null ?>>Hot</option>
            <option value="price" <?= $filters['orderBy']=='price' ? 'selected' : null ?>>Price</option>
            <option value="CR" <?= $filters['orderBy']=='CR' ? 'selected' : null ?>>Creator Rating</option>
        </select>
        </div>
            <h4 class="pull-right">Sort :</h4>
        </div>
        <?php Pjax::begin(); ?>

        <?php foreach ($orders as $order): ?>
            <div class="panel panel-default" <?= $ordersReqNum[$order->id] ? 'style="border: 1px solid red"' : null?> >
                <div class="panel-body">
                    <div class="text-primary" style="font-size: 20px;"><a href="<?= \yii\helpers\Url::to(['orders/order', 'orderId' => $order->id])?>" ><?= Html::encode($order->Title)?></a>
                    </div>
                    <hr>
                    <div><?= $order->description ?></div>
                    <hr>
                    <h5><span class="glyphicon glyphicon-time"></span> Post by <?=$order->user->login; ?>, <?=  Yii::$app->formatter->asDate($order->timePosted) ?>
                    <span class="pull-right text-success">Price <?= isset($order->price) ? $order->price : 'Negotiatiable' ?></span>
                    </h5>

                </div>
            </div>
        <?php endforeach; ?>
        <?php Pjax::end(); ?>
        <?php }else{
            ?> <h2 class="text-center"><small>There is no active orders in choosen city</small></h2>
            <div class="mt-5 text-center" style="margin: 20px 20px 30px auto;"><?= Html::a('Add order', ['orders/add_order'],
                    ['class' => 'btn btn-success text-center']);?></div>
        <?}?>
        <?= Html::a('Обновить', ['orders/filter_orders'], ['class' => 'hidden', 'id' => 'filterRefresh']) ?>
        </div>

    </div>
    <div class="col-lg-2">

    </div>

</div>

</div>