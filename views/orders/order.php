<?php
use yii\bootstrap\Html;

$this->title = $order->Title;
$this->registerJsFile('@web/assets/c1a6917c/jquery.js');
$this->registerJsFile('@web/js/request.js');
$this->registerJsFile('@web/js/rating.js');
?>
<div class="row">
<div class="col-sm-8">
      <hr>
      <div class="shortProfile">
          <?php
          if(isset($order->user->logo)){
          ?><img src="<?=Yii::$app->request->baseUrl?>/images/avatars/q<?=$order->user->login?>_1.jpg"><?php
          }else{
              ?><img src="<?=Yii::$app->request->baseUrl?>/images/logos/user_logo_1.png" class="img-circle text-center" height="64" width="64" style="float: left; margin-right: 30px;"><?
          }
          ?>
        <h4 class="text-left"><small><?= $order->user->name?> <?=$order->user->lastname?>
            <span class="pull-right"> Customer rating: <?= isset($order->user->customer_rating) ? $order->user->customer_rating : 0 ?></span></small></h4>
        <h4 class="text-left"><small>Registrated <?= Yii::$app->formatter->asRelativeTime($order->user->registration_date);?></small>
        <span class="pull-right text-success">
           Price : <?= $order->price ?>
        </span></h4>

      </div>
      <hr>
      <h2><?= $order->Title; ?>
          <?php
          if(!$isUsersOrder && !$isRequestSend){ ?>
            <button class="btn btn-success pull-right" data-toggle = 'modal' data-target = '#requestModal' style="float:left;">Send Request</button>
          <?}elseif($isRequestSend && !$isReqConfirmed && !isRRequestConf){?>
            <p class="btn btn-default text muted pull-right disabled" style="float:left;">You already send request</p>
          <?}elseif($isReqConfirmed && !$isRRequestSend && \app\models\Order::isUserExecutor(Yii::$app->user->identity->id, $order->id)){

              ?>
              <button class="btn btn-success pull-right" data-toggle = 'modal' data-target = '#reverseRequestModal' style="float:left;">Order done</button>
          <?
          }elseif($isRRequestSend && !$isUsersOrder){?>
              <button class="btn btn-default pull-right disabled" style="float:left;">Fulfillment request send</button>
          <?}elseif($isRRequestSend && $isUsersOrder){?>
              <button class="btn btn-success pull-right" data-toggle = 'modal' data-target = '#reverseRequestConfModal' style="float:left;">Confirm order execution</button>
              <button class="btn btn-danger pull-right" data-toggle = 'modal' data-target = '#reverseRequestRejModal' style="float:left;">Reject request</button>
          <?}elseif($order->orders_users->status == 1 && ($order->orders_users->user->id == Yii::$app->user->identity->id || $isUsersOrder)){
              ?><button class="btn btn-default pull-right disabled" style="float:left;">Order finished</button><?
              if(isset($commentModel)){
              ?>
              <button class="btn btn-success pull-right" data-toggle = 'modal' data-target = '#commentModal' style="float:left;">Comment</button><?
              }
          }elseif($order->orders_users->status === 0){
              ?><button class="btn btn-default pull-right disabled" style="float:left;">Order finished as unsuccessfull</button><?
          }?>
      </h2>
      <h5><span class="glyphicon glyphicon-time"></span> Post by <?=$order->user->login; ?>, <?=  Yii::$app->formatter->asDate($order->timePosted) ?></h5>
      <hr>
      <p><?= $order->description ?></p>
      <br>
      <hr>
      <div class="orderDateInfo">
          <h4><small>Order date: </small><?= $order->orderDate?> <small>Starts at:</small> <?= $order->timeMin?> <small>Ends at:</small> <?= $order->timeMax ?></h4>
      </div>
      <hr>
</div>

    <div class="col-sm-4">
        <h2 class="text-center"><small>Delivery Items</small></h2>
        <?php if(isset($order->items)){ ?>
            <div class="orderItems">
                <ul class="list-group">
                    <?php
                    foreach($order->items as $item){
                        ?><li class="list-group-item"><?= $item->description ?></li><?
                    }
                    ?>
                </ul>
            </div>
        <?php
        }else{
            ?><h3 class="text-center"><small>Items not set</small></h3><?
        }
        ?>
    </div>
    </div>
<div class="row">
    <div class="col-sm-8">
        <div class="request">

                <?php
                if(!$isRRequestSend && $isUsersOrder){
                    ?><h2 class="text-center"><small>Requests</small></h2> <?
                    if(isset($requests[0])){
                    foreach($requests as $request){
                        if($request->status == null && $order->orders_users->status == 1){continue;}
                        ?>
                        <div class="panel panel-default" style="padding: 5px;">
                           <h4 class="requestTitle bottom-lined"> Request from <a class="text-primary" href="delivery/profile&id=<?=$request->userId?>"><?= $request->login; ?></a>

                               <?php
                               if(!(isset($request->status)) && !$isRRequestSend){

                               ?>
                               <? if(!$isOrderInProc){?>
                               <button class="requestConfirmButton btn btn-success pull-right" data-id='<?=$request->id;?>' data-toggle = 'modal' data-target = '#requestConfirmModal' style="float:left;">Confirm request</button>
                               <? } ?>
                                <button class="requestCancelButton btn btn-danger pull-right" data-id='<?=$request->id;?>' data-toggle = 'modal' data-target = '#requestCancelModal' >Deny request</button></h4>
                                   <?} elseif($request->status=='accept'){
                                   ?>
                                   <p class="pull-right text">Request Confirmed</p>
                               <?
                               } else{
                                   ?>
                                   <p class="pull-right text">Request canceled</p>
                                   <?
                               }
                               ?>
                           <hr>
                           <div class="request-body">
                               <?= $request->description; ?>
                           </div>
                          <hr>
                            <div class="request-footer">
                                <?= $request->timePosted; ?>
                            </div>

                        </div>
                    <?
                    }
                    } else{
                        ?><h3 class="text-center"><small>Your order havent any request</small></h3> <?
                    }
                }?>
            </div>
        </div>
    </div>

<?if($order->orders_users->status == 1 && ($order->orders_users->user->id == Yii::$app->user->identity->id || $isUsersOrder) && isset($commentModel)){?>
    <!-- Comment Modal -->
    <div class="modal fade in" id="commentModal" role="dialog" style="position:fixed;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Comment order execution</h4>
                </div>
                <div class="modal-body">
                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'addRequest',
                        'action' => 'add_comment?orderId='.$order->id,
                    ]);
                    ?>
                    <?= $form->field($commentModel, 'text')->textArea();?>
                    <?= $form->field($commentModel, 'rating')->hiddenInput(['id' => 'ratingField']);?>
                    <label>Rating</label>
                    <div class="pull-right rating">
                    <?for($i=0; $i<10; $i++){
                        ?><img class="ratingStar" src="<?=Yii::$app->request->baseUrl?>/images/star.png"  width="20px" height="20px" data-id="<?= $i?>"><?
                    }?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::submitButton('Submit', ['class' => 'btn btn-success requestFormSubmit']); ?>
                    <?php \yii\widgets\ActiveForm::end();?>
                </div>
            </div>

        </div>
    </div>
<?}?>

<?if(!$isUsersOrder && !$isRequestSend){ ?>
    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" role="dialog" style="position:fixed;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Request send</h4>
                </div>
                <div class="modal-body">
                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'addRequest',
                        'action' => 'request',
                    ]);
                    ?>
                    <?= $form->field($requestModel, 'description')->textArea();?>
                    <?= $form->field($requestModel, 'orderId')->hiddenInput(['value' => $order->id])->label('Order id', ['class' => 'hidden']); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::submitButton('Submit', ['class' => 'btn btn-success requestFormSubmit']); ?>
                    <?php \yii\widgets\ActiveForm::end();?>
                </div>
            </div>

        </div>
    </div>
<?}?>
<?if($isReqConfirmed && !$isRRequestSend && \app\models\Order::isUserExecutor(Yii::$app->user->identity->id, $order->id)){?>
    <!-- Request Modal -->
    <div class="modal fade" id="reverseRequestModal" role="dialog" style="position:fixed;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Reverse Request send</h4>
                </div>
                <div class="modal-body">
                  Are you sure you want to confirm order fulfillment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::a('Submit', 'fulfillment_request?orderId='.$order->id , ['class' => 'btn btn-success reverseRequestSubmit']); ?>
                </div>
            </div>

        </div>
    </div>
<?}?>

<?if(!$isRRequestSend && $isUsersOrder){?>
    <!-- Request Confirm Modal -->
    <div class="modal fade" id="requestConfirmModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Request confirmation</h4>
                </div>
                <div class="modal-body">
                   <h4> Are you sure you want to accept this request?</h4>
                   <div class="alert alert-warning">
                       After confirmation you couln't change your mind or delete order!
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::a('Confirm', 'confirm_request?orderId='.$order->id, ['class' => 'modalConfirmReqButton btn btn-success']) ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Request Deny Modal -->
    <div class="modal fade" id="requestCancelModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Request cancel</h4>
                </div>
                <div class="modal-body">
                    <h4> Are you sure you want to cancel this request?</h4>
                    <div class="alert alert-warning">
                        After that you couldnt back up this request.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::a('Delete', 'deny_request?orderId='.$order->id, ['class' => 'modalCancelReqButton btn btn-success']) ?>
                </div>
            </div>

        </div>
    </div>
<?}?>
<?if($isRRequestSend && $isUsersOrder){?>
    <!-- Request Fulfillment confirm Modal -->
    <div class="modal fade" id="reverseRequestConfModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Order fulfillment</h4>
                </div>
                <div class="modal-body">
                    <h4> Are you sure you want to confirm order fulfillment?</h4>
                    <div class="alert alert-warning">
                        You coulnt change your mind. Order would be considered as done.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::a('Confirm', 'fulfillment_confirm?orderId='.$order->id, ['class' => 'modalCancelReqButton btn btn-success']) ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Request Fulfillment reject Modal -->
    <div class="modal fade" id="reverseRequestRejModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Order fulfillment reject</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-warning">
                        <h4> Are you sure you want to reject order fulfillment request?</h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    <?= Html::a('Reject', 'fulfillment_reject?orderId='.$order->id, ['class' => 'modalCancelReqButton btn btn-danger']) ?>
                </div>
            </div>

        </div>
    </div>
<?}?>
</div>