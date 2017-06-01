<?php
$this->title = "Orders";
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
    <h1>Countries</h1>
    <ul>
        <?php foreach ($orders as $order): ?>
            <li>
                <?= Html::encode("{$order->Title} ({$order->creatorLogin})") ?>:
                <?= $order->timePosted ?>
            </li>
        <?php endforeach; ?>
    </ul>

<?= LinkPager::widget(['pagination' => $pagination]) ?>