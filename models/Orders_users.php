<?php
namespace app\models;
use yii\db\ActiveRecord;

class Orders_users extends ActiveRecord{
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrder(){
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }
}