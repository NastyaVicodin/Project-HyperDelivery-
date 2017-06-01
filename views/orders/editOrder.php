<?php
use yii\bootstrap\ActiveForm;

$this->title = "Orders";
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/assets/c1a6917c/jquery.js');
$this->registerJsFile('@web/js/editOrder.js');
?>

<div class="row">
    <h2 class="text-center"><small>Order Edit</small></h2>
    <div class="col-sm-1">
    </div>
    <div class="col-sm-6">
        <?
        $form = ActiveForm::begin([
            'id' => 'addOrder-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}<br><div>{input}</div><div>{error}</div>",
                'labelOptions' => ['class' => 'control-label'],
            ],
        ]);?>
        <?= $form->field($model, 'title')->textInput(['autofocus' => true, 'value' => isset($order) ? $order->Title : null]); ?>
        <?= $form->field($model, 'orderDate')->widget(\yii\jui\DatePicker::className(), [
            'value' => $order->orderDate,
            'language' => 'ru',
            'dateFormat' => 'yyyy-MM-dd',
        ]);?>
        <?= $form->field($model, 'description')->textarea(['value' => isset($order) ? $order->description : null]);?>
        <?= $form->field($model, 'price')->textInput(['placeholder' => 'Negotiable', 'value' => isset($order) ? $order->price : null]); ?>
        <?= $form->field($model, 'items')->hiddenInput(['value' => isset($itemsStr) ? $itemsStr : null, 'class' => 'itemsField itemsEdit',
            ])->label('Items', ['class' => 'hidden']); ?>
        <div class="text-center"><?= \yii\bootstrap\Html::submitButton('Save', ['class' => 'addOrderButton btn btn-primary']); ?></div>
        <?ActiveForm::end();?>
    </div>
    <div class="col-sm-5">
        <label class="control-label">
            Items description
        </label>
        <div class="items"">
            <div class="item" data-id="1">
                <input type="text" class="itemDescription form-control" data-id="1" placeholder="Item description">
                <div><span class="close ItemDel" data-id="1">&times</span></div>
            </div>
        </div>
        <p class="text-center"><p class="itemAddButton btn btn-default"><span class="glyphicon glyphicon-plus"></span></button></p>
    </div>
</div>