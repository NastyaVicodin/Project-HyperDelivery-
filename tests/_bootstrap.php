<?php
// This is global bootstrap for autoloading
$basePath = "https://room217.herokuapp.com/web/";
new yii\web\Application(require(__DIR__ . '/../config/web.php'));