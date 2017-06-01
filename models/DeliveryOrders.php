<?php
namespace app\models;

use yii\base\Model;
use yii\db\Query;
use yii\db\QueryBuilder;
use app\models\Orders;

class DeliveryOrders extends Model{
    public $date;
    public $timeStart;
    public $timeEnd;
    public $minPrice;
    public $offset;

    private  $orders = array();
    public function rules(){
        return [
            ['date', 'date', 'format' => 'yyyy-M-d'],
            ['minPrice', 'integer']

        ];
    }

    public function getOrders($sortMethod = null, $date = null, $time = null, $price = null, $search = null){

            $orders = Orders::find()->select('orders.*, user.customer_rating')->innerJoin('user', 'orders.userId = user.id')
                ->where(['>=', 'orderDate', date('Y-m-d')]);

            if(isset($this->offset)){
                $orders =  $orders->offset($this->offset);
            }
            if(!\Yii::$app->user->isGuest){
                $orders =  $orders->andWhere(['orders.city' => \Yii::$app->user->identity->city]);
            }
            if(isset($date)){
                $orders =  $orders->andWhere(['orderDate' => $date]);
            }
            if(isset($time)){
                $orders = $orders->andWhere(['>', 'timeMin', $time]);
            }
            if(isset($price)){
                $orders = $orders->andWhere(['>', 'price', $price]);
            }
            if(isset($search)){
                $orders = $orders->andWhere(['like', 'Title', $search])->orWhere(['like', 'orders.description', $search]);
            }
            if(isset($sortMethod)){
                if($sortMethod == 'new'){
                    $orders = $orders->orderBy(['timePosted' => SORT_DESC]);
                }
                if($sortMethod == 'hot'){
                    $orders = $orders->orderBy(['orderDate' => SORT_DESC, 'timeMin' => SORT_DESC]);
                }
                if($sortMethod == 'price'){
                    $orders = $orders->orderBy(['price' => SORT_DESC]);
                }
                if($sortMethod == 'CR'){
                    $orders = $orders->orderBy(['user.customer_rating' => SORT_DESC]);
                }
            }
            $orders = $orders->all();
        $this->orders = $orders;
        return $orders;
    }

    public function getUserOrders(){
        $ordersQuery = Orders::find()->where([
            '>=', 'orderDate', date('Y-m-d'),
            ])->andWhere(['creatorLogin' => \Yii::$app->user->identity->login])->all();
        $orders = array();
        foreach($ordersQuery as $order){
            if($order->orders_users->status == null || !Order::isOrderInProcess($order->id)){
                array_push($orders, $order);
            }
        }
        return $orders;
    }

    public function getHistoryOrders(){
        $ordersId = Orders_users::find()->select('order_id')
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['status' => 1])
            ->all();
        $orders = array();
        if(isset($ordersId)){
            foreach($ordersId as $orderId){
                array_push($orders, Orders::find()->where(['id' => $orderId])->one());
            }
        }
        return $orders;
    }

    public function getUserHistoryOrders(){
        $userOrders = Orders::find()->where(['userId' => \Yii::$app->user->identity->id])->all();
        $orders = array();
        if(isset($userOrders)){
            foreach($userOrders as $order){
                if($order->orders_users->status != null){
                    array_push($orders, $order);
                }
            }
        }
        return $orders;
    }

    public function getUserRequestsId($status = null){
        $ordersId = Order_requests::find()->select('order_id')
            ->where([
            'user_id' => \Yii::$app->user->identity->id,
            ])->andWhere(['status' => $status])->all();
        return $ordersId;
    }

    public function getUserOrdersInProcess($userId){
        $orderId = Orders_users::find()->select('order_id')->where(['user_id' => $userId])->andWhere(['status' => null])->all();
        $orders = array();
        foreach($orderId as $order){
            array_push($orders, Orders::find()->where(['id' => $order->order_id])->one());
        }
        return $orders;
    }

    public function getUserRequests(){
        $ordersId = $this->getUserRequestsId();
        $orders = array();
        if(isset($ordersId)){
            foreach($ordersId as $orderId){
                if(!Orders_users::find()->where(['status' => '1'])->andWhere(['order_id' => $orderId])->exists()){
                    array_push($orders, Orders::find()->where(['id' => $orderId])->one());
                }
            }
        }
        return $orders;
    }

    public function ordersCount(){
        return isset($this->orders)? count($this->orders): null;
    }
}