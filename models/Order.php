<?php
namespace app\models;

use yii\base\Model;

class Order extends Model{
    public $id;
    public $title;
    public $orderDate;
    public $description;
    public $price;
    public $startHour;
    public $endHour;
    public $startMinutes;
    public $endMinutes;
    public $items;

    public $requestNum;

    public function rules(){
        return[
            [['title', 'orderDate', 'description', 'startHour', 'endHour', 'startMinutes', 'endMinutes'], 'required'],
            [['price', 'items'], 'safe'],
            ['title', 'string', 'length' => [6, 150]],
            ['title', 'string', 'length' => [20, 1000]],
            ['price', 'integer', 'min' => '1', 'max' => '1000000'],
            [['orderDate'], 'date', 'format' => 'Y-m-d'],
            ['orderDate', 'validateOrderDate'],
            [['startHour'], 'integer', 'min' => '0', 'max' => '23'],
            [['endHour'], 'integer', 'min' => isset($this->startHour) ? $this->startHour.'' : '0', 'max' => '23'],
            [['startMinutes'], 'integer', 'min' => '0', 'max' => '59'],
            [['endMinutes'], 'integer', 'min' => ($this->startHour == $this->endHour) ? $this->startMinutes.'' : '0' , 'max' => '59'],
        ];
    }
    public function formName(){
        return 'addOrder-form';
    }

    public function validateOrderDate($attribute, $params){
        if($this->orderDate < date('Y-m-d')){
            $this->addError($attribute, 'Date shouldnt refer to the past');
        }
    }

    public function addOrder(){
        $order = new Orders();
        $this->id = Orders::find()->select('id')->max('id') + 1;
        $order->Title = $this->title;
        $order->timePosted = $this->getTimePosted();
        $order->orderDate = $this->orderDate;
        $order->timeMin = $this->timeMin;
        $order->description = $this->description;
        $order->creatorLogin = $this->getCreatorLogin();
        $order->price = $this->price;
        $order->city = $this->getUserCity();
        $order->timeMin = $this->generateDate('start');
        $order->timeMax = $this->generateDate('end');
        $order->userId = \Yii::$app->user->identity->id;

        if($order->save()){
            if($this->addItems($this->getItems())){
                return true;
            }
        }
        return false;

    }

    public function addItems($items){
        if(isset($items)){
            foreach($items as $item){
                if($item != ''){
                    $itm = new Items();
                    $itm->description = $item;
                    $itm->order_id = $this->id;
                    $itm->save();
                }
            }
        }
        return true;
    }

    public static function getOrderById($id){
        $order = Orders::find()->where(['id' => $id])->one();
        return $order;
    }

   public function getItemsByOrderId($id){
       $items = Items::find()->where([
           'order_id' => $id
       ])->all();
       $itemsStr = '';
       foreach($items as $item){
           $itemsStr .= $item->description.'&';
       }
       return $itemsStr;
   }

    public static function isUsersOrder($id){
        $permission = Orders::find()->where(['id' => $id])->andWhere(['creatorLogin' => \Yii::$app->user->identity->login])->exists();
        return $permission;
    }

    public static function isUserExecutor($userId, $orderId){
        $perm = Orders_users::find()->where(['user_id' => $userId])->andWhere(['order_id' => $orderId])->exists();
        return $perm;
    }

    public static function isOrderInProcess($id){
        $perm = Orders_users::find()->where(['order_id' => $id])->exists();
        return $perm;
    }

    public function deleteOrderById($id){
        $order = Orders::findOne($id);
        if($order->delete()){
            return true;
        }else{
            return false;
        }
    }

    public function editOrder($id){
        $order = $this->getOrderById($id);
        $order->Title = $this->title;
        $order->orderDate = $this->orderDate;
        $order->description = $this->description;
        $order->price = $this->price;
        if($order->save()){
            return true;
        }
    }

    public static function countEmplUserOrders($id){
        $count = Orders_users::find()->where(['user_id' => $id])->andWhere(['status' => 1])->count();
        return $count;
    }

    public static function countUserDoneOrders($id){
        $count = Orders::find()->select('orders.id, orders_users.user_id, orders_users->status')
            ->innerJoin('orders_users', 'orders.id = orders_users.order_id')
            ->where(['orders.userId' => $id])
            ->andWhere(['orders_users.status' => 1])->count();
        return $count;
    }

    public function getItems(){
        return $items = explode('&', $this->items);
    }

    public function getCreatorLogin(){
        return \Yii::$app->user->identity->login;
    }

    public function generateDate($flag){
        if($flag == 'start'){
            return $this->startHour.':'.$this->startMinutes.':00';
        }
        if($flag == 'end'){
            return $this->endHour.':'.$this->endMinutes.':00';
        }
    }

    public function getUserCity(){
        return \Yii::$app->user->identity->city;
    }

    public function getTimePosted(){
        return date("Y-m-d H:i:s");
    }
}