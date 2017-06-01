<?php
namespace app\models;
use yii\db\ActiveRecord;

class Orders extends ActiveRecord{
    public function getOrder_requests(){
        return $this->hasMany(Order_requests::className(), ['order_id' => 'id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getItems(){
        return $this->hasMany(Items::className(), ['order_id' => 'id']);
    }

    public function getOrders_users(){
        return $this->hasOne(Orders_users::className(), ['order_id' => 'id']);
    }
}