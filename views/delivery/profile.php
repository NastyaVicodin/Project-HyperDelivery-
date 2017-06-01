<?php
use yii\helpers\Html;

$this->title = "Profile";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='row'>
    <div class="col-lg-4">
        <h3 class="text-center text-muted">Profile</h3>
        <div class="panel panel-default">
            <h3 class="text-center"><?=$user->login?></h3>
            <?php if($user->logo){
                ?>
                <p class="text-center" style="margin:10px;"><img src="<?=Yii::$app->request->baseUrl?>/images/avatars/q<?=$user->login?>_1.jpg" class="img-rounded" height="128" width="128"> </p>

            <?} else{?>
                <p class="text-center" style="margin:10px;">
                    <img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle" height="128" width="128"></p>
            <?php } ?>
            <p class="text-primary text-center">Name:<p class="text-danger text-center"><?=$user->name?></p></p>
            <p class="text-primary text-center">Last name:<p class="text-danger text-center"><?=$user->lastname?></p></p>
            <p class="text-primary" style="margin-left: 10px;">Customer rating: <?= isset($user->customer_rating) ? $user->customer_rating : 0?>
                <span class="badge"><?= $countUserOrd ?></span>
            </p>
            <p class="text-primary" style="margin-left: 10px;">Employee rating: <?= $user->employee_rating ? $user->emoloyee_rating : 0?>
                <span class="badge"><?= $countEmplOrd ?></span>
            </p>
        </div>
    </div>
    <div class="col-lg-8">
        <h2 class="text-center"><small>Comments</small></h2>
        <ul class="nav nav-tabs" role="tablist">
            <li  class="active"><a href="#OrdersComm" aria-controls="OrdersComm" data-toggle="tab">As customer</a></li>
            <li><a href="#EmplComm" aria-controls="EmplComm" role="tab" data-toggle="tab">As employee</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="OrdersComm">
                <?php
                if($userCustComm){
                    foreach($userCustComm as $comm){ ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists('images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p><?=  $comm->text?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                                ?><img src="images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
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
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-sm-2 text-center">
                                    <?php if(file_exists('images/avatars/'.$comm->creatorUser->login.'_1.png')){?>
                                        <img src="images/avatars/<?=$comm->creatorUser->login?>_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}else{?>
                                        <img src="images/logos/user_logo_1.png" class="img-circle" height="65" width="65" alt="Avatar">
                                    <?}?>
                                </div>
                                <div class="col-sm-10">
                                    <h4><?= $comm->creatorUser->login ?> <small><?= Yii::$app->formatter->asDatetime($comm->timePosted) ?></small></h4>
                                    <p><?=  $comm->text . 'lolol'?></p>
                                    <br>
                                    <p class="text-left"><h4 class="text-warning">Order: <?= $comm->order->Title; ?>
                                        <p class="pull-right">Rating: <? for($i=1; $i <= $comm->rating; $i++){
                                                ?><img src="images/gold_star.png" width="20px" height="20px" data-id="<?=$i;?>"><?
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
        </div>
    </div>
</div>
</div>