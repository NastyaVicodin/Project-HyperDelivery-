<?php
use yii\helpers\Html;

$this->title = "Profile";
$this->registerJsFile('assets/c1a6917c/jquery.js');
$this->registerJsFile('js/commentEdit.js');
?>

<div class='row'>
    <div class="col-lg-4">
        <h3 class="text-center text-muted">Profile</h3>
        <div class="panel panel-default">
            <?php if(isset(Yii::$app->user->identity->logo)){
            ?>
                <p class="text-center" style="margin:10px;"><img src="<?=Yii::$app->request->baseUrl?>/images/avatars/q<?=Yii::$app->user->identity->login?>_1.jpg" class="img-rounded" height="128" width="128"> </p>

            <?} else{?>
            <p class="text-center" style="margin:10px;">
                <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="128" width="128"></p>
            <?php } ?>
            <p class="text-primary text-center">Name:<p class="text-danger text-center"><?=Yii::$app->user->identity->name?></p></p>
            <p class="text-primary text-center">Last name:<p class="text-danger text-center"><?=Yii::$app->user->identity->lastname?></p></p>
            <p class="text-primary" style="margin-left: 10px;">Customer rating: <?= isset(Yii::$app->user->identity->customer_rating) ? Yii::$app->user->identity->customer_rating : 0?>
                <span class="badge"><?= $countUserOrd ?></span>
            </p>

            <p class="text-primary" style="margin-left: 10px;">Employee rating: <?= $user->employee_rating ? $user->emoloyee_rating : 0?>
                <span class="badge"><?= $countEmplOrd ?></span>
            </p>
        </div>
    </div>
    <div class="col-lg-8">
        <h3 class="text-center text-muted">History</h3>
        <p class="text-center">
        <ul class="nav nav-tabs" role="tablist">
            <li  class="active"><a href="#doneOrders" aria-controls="doneOrders" data-toggle="tab">Done Orders</a></li>
            <li><a href="#historyOrders" aria-controls="historyOrders" role="tab" data-toggle="tab">My Orders</a></li>
        </ul>
        </p>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="doneOrders">
                <?php
                if($doneOrders){
                    foreach($doneOrders as $order){?>
                        <div class="panel panel-default" <?= $ordersReqNum[$order->id] ? 'style="border: 1px solid red"' : null?> >
                            <div class="panel-body">
                                <div class="text-primary" style="font-size: 20px;"><a href="<?= \yii\helpers\Url::to(['orders/order', 'orderId' => $order->id])?>" ><?= Html::encode($order->Title)?></a>
                                </div>
                                <hr>
                                <div><?= $order->description ?></div>
                                <hr>
                                <?= \Yii::$app->formatter->asDatetime($order->timePosted); ?>

                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You havent done orders</small>
                    </h2><?
                }
                ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="historyOrders">
                <?php
                if($userOrders){
                    foreach($userOrders as $order){ ?>
                        <div class="panel panel-default" <?= $ordersReqNum[$order->id] ? 'style="border: 1px solid red"' : null?> >
                            <div class="panel-body">
                                <div class="text-primary" style="font-size: 20px;"><a href="<?= \yii\helpers\Url::to(['orders/order', 'orderId' => $order->id])?>" ><?= Html::encode($order->Title)?></a>
                                </div>
                                <hr>
                                <div><?= $order->description ?></div>
                                <hr>
                                <?= \Yii::$app->formatter->asDatetime($order->timePosted); ?>

                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You didnt create any order</small>
                    </h2><?
                }
                ?></div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
        <h2 class="text-center"><small>Comments</small></h2>
        <h4 class="text-muted">My comments<p class="pull-right" style="margin-right: 20px;">Send comments</p></h4>
        <ul class="nav nav-tabs" role="tablist">
            <li  class="active"><a href="#OrdersComm" aria-controls="OrdersComm" data-toggle="tab">As customer</a></li>
            <li><a href="#EmplComm" aria-controls="EmplComm" role="tab" data-toggle="tab">As employee</a></li>
            <li class="pull-right"><a href="#sendCustComm" aria-controls="s" role="tab" data-toggle="tab">For customers</a></li>
            <li class="pull-right"><a href="#sendEmplComm" aria-controls="s" role="tab" data-toggle="tab">For emplyees</a></li>
        </ul>
        <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade in active" id="OrdersComm">
                <?php
                if($userCustComm){
                    foreach($userCustComm as $comm){ ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists(Yii::$app->request->baseUrl.'/images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p><?=  $comm->text?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                                ?><img src="<?=Yii::$app->request->baseUrl?>/images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
                                            }
                                            ?></p></h4></p>
                                </div>
                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You havent comments as customer</small>
                    </h2><?
                } ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="EmplComm">
                <?php
                if($userEmplComm){
                    foreach($userEmplComm as $comm){ ?>
                        <div class="panel panel-default" data-id="<?= $comm->id ?>">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists('images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p class="commText"><?=  $comm->text . 'lolol'?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                                ?><img src="<?=Yii::$app->request->baseUrl?>/images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
                                            }
                                            ?></p></h4></p>
                                </div>
                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You havent comments as employee</small>
                    </h2><?
                } ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="sendEmplComm">
                <?php
                if($sendEmplComm){
                    foreach($sendEmplComm as $comm){ ?>
                        <div class="panel panel-default sendEmplComm" data-id="<?= $comm->id ?>">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists(Yii::$app->request->baseUrl.'/images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <div class="dropdown pull-right">
                                         <span class="glyphicon glyphicon-cog pull-right" id="commOper"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </span>
                                        <ul class="dropdown-menu" aria-labelledby="commOper">
                                            <li>
                                                <!-- Modal button -->
                                                <?= Html::a('Delete', '', ['class' => 'commDelButton', 'data-id' => $comm->id,  'data-toggle' => 'modal', 'data-target' => '#myModal']); ?>


                                            </li>
                                            <li>
                                                <?=Html::a('Edit', null, ['class' => 'commEditButton', 'data-id' => $comm->id])?>
                                            </li>
                                        </ul>
                                    </div>
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p class="commText"><?=  $comm->text ?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                                ?><img src="<?=Yii::$app->request->baseUrl?>/images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
                                            }
                                            ?></p></h4></p>
                                </div>
                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You didnt send comments as customer</small>
                    </h2><?
                } ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="sendCustComm">
                <?php
                if($sendCustComm){
                    foreach($sendCustComm as $comm){ ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists(Yii::$app->request->baseUrl.'images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p><?=  $comm->text?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                            ?><img src="<?=Yii::$app->request->baseUrl?>/images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
                                        }
                                    ?></p></h4></p>
                                </div>

                            </div>
                        </div>
                    <? }
                }else{
                    ?><h2 class='text-center text-warning'>
                        <small>You didnt send comments as employee</small>
                    </h2><?
                } ?>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
    </div>
</div>