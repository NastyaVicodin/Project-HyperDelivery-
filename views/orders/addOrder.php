<?php
use yii\bootstrap\ActiveForm;

$this->title = "Orders";
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/assets/c1a6917c/jquery.js');
$this->registerJsFile('@web/js/orderAdd.js');
?>

<div class="row">
    <div class="col-lg-1">
    </div>
    <div class="col-lg-6">
        <h2><small>Order info</small></h2>
        <?
        $form = ActiveForm::begin([
            'id' => 'orderAdd-form',
            'options' => ['class' => 'form-vertical'],
            'fieldConfig' => [
            'template' => "{label}<br><div>{input}</div><div>{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
        ]);
        ?>
        <?= $form->field($model, 'title')->textInput(['autofocus' => true, 'value' => isset($order) ? $order->Title : null]); ?>
        <?= $form->field($model, 'orderDate')->widget(\yii\jui\DatePicker::className(), [
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd'
        ]);?>
        <p class="text-center">Time period</p>
        <div class="form-group row">
            <div class="col-sm-1">
        <?= $form->field($model, 'startHour')->textInput(['placeholder' => 'HH', 'style' => 'width:49px; display:inline;'])->label('startHour', ['class' => 'hidden']); ?>

            </div>

            <div class="col-sm-1">
        <?= $form->field($model, 'startMinutes')->textInput(['placeholder' => 'MM', 'style' => 'width:49px;  display:inline;'])->label('starMinutes', ['class' => 'hidden']); ?>
            </div>
            <div class="col-sm-1">
             </div>
            <div class="col-sm-1">
                <?= $form->field($model, 'endHour')->textInput(['placeholder' => 'HH','style' => 'width:49px; display:inline;'])->label('endHour', ['class' => 'hidden']); ?>

            </div>
            <div class="col-sm-1">
                <?= $form->field($model, 'endMinutes')->textInput(['placeholder' => 'MM', 'style' => 'width:49px;  display:inline;'])->label('endMinutes', ['class' => 'hidden']); ?>
            </div>
        </div>
        <?= $form->field($model, 'description')->textarea();?>
        <?= $form->field($model, 'price')->textInput(['placeholder' => 'Negotiable']); ?>
        <?= $form->field($model, 'items')->hiddenInput(['class' => ' itemsField'])->label('Items', ['class' => 'hidden']); ?>
    </div>
    <div class="col-lg-5">
        <h2 class="text-center"><small>Items</small></h2>
        <label class="control-label">
            Items description
        </label>
        <div class="items">
            <div class="item" data-id="1">
                <input type="text" class="itemDescription form-control" data-id="1" placeholder="Item description">
                <div><span class="close ItemDel" data-id="1">&times</span></div>
            </div>
         </div>
        <p class="text-center"><p class="itemAddButton btn btn-default"><span class="glyphicon glyphicon-plus"></span></button></p>
    </div>
</div>
<div class="row">
    <div class="text-center"><?= \yii\bootstrap\Html::submitButton('Add Order', ['class' => 'addOrderButton btn btn-primary']); ?></div>
    <?ActiveForm::end();?>
</div>