<?php
namespace app\models;
use yii\db\ActiveRecord;

class Order_requests extends ActiveRecord{
    public function getOrder(){
        return $this->hasOne(Order::className(), ['order_id' => 'id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}