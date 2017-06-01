<?php
namespace app\models;
use yii\db\ActiveRecord;

class Comments extends ActiveRecord{
    public function getCreatorUser(){
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    public function getOrder(){
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }
}
?>