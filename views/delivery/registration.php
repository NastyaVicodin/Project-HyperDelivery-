<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = 'Registration';
$this->params['breadcrumbs'][] = 'Registration';


?>
<div class="text-center">
    <h1><?= Html::encode($this->title)?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'registration-form',
        'options' => ['class' => 'form-horizontal text-center'],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-4\"></div>{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8 col-lg-offset-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]);
    ?>
    <?= $form->field($model, 'login')->textInput();?>
    <?= $form->field($model, 'password')->passwordInput();?>
    <?= $form->field($model, 'email')->textInput();?>
    <?= $form->field($model, 'name')->textInput();?>
    <?= $form->field($model, 'lastname')->textInput();?>
    <?= $form->field($model, 'birthYear')->dropDownList([
        '2002' => '2002',
        '2001' => '2001',
        '2000' => '2000',
        '1999' => '1999',
        '1998' => '1998',
        '1997' => '1997',
        '1996' => '1996',
        '1995' => '1995',
        '1994' => '1994',
        '1993' => '1993',
        '1992' => '1992',
        '1991' => '1991',
        '1990' => '1990',
        '1989' => '1989',
        '1988' => '1988',
        '1987' => '1987',
        '1986' => '1986',
        '1985' => '1985',
        '1984' => '1984',
        '1983' => '1983',
        '1982' => '1982',
        '1981' => '1981',
        '1980' => '1980',
    ]); ?>
    <?= $form->field($model, 'birthMonth')->dropDownList([
        'January' => 'January',
        'February' => 'February',
        'March' => 'March',
        'April' => 'April',
        'May' => 'May',
        'June' => 'June',
        'July' => 'July',
        'August' => 'August',
        'September' => 'September',
        'October' => 'October',
        'November' => 'November',
        'December' => 'December',
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'registration-button']) ?>
    </div>
    <?
    ActiveForm::end();
    ?>
</div>