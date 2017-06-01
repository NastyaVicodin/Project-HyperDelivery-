<?php
namespace app\models;

use yii\base\ErrorException;
use yii\base\Model;


class Request extends Model{
    public $id;
    public $orderId;
    public $userId;
    public $login;
    public $description;
    public $timePosted;
    public $status;

    public function rules(){
        return [[['orderId','description'], 'required']];
    }

    public function formName(){
        return 'addRequest';
    }

    public function deleteRequest($orderId){
        $request = Order_requests::find()
            ->where(['order_id' => $orderId])
            ->andWhere(['user_id' => \Yii::$app->user->identity->id])
            ->one();
        if($request->status == null){
            $request->delete();
            return true;
        }
        return false;
    }

    public function addRequest($orderId = null){
        if(!Order::isUsersOrder(isset($orderId) ? $orderId : $this->orderId)){
            $request = new Order_requests();
            $request->order_id = isset($orderId) ? $orderId : $this->orderId;
            $request->user_id = \Yii::$app->user->identity->id;
            $request->description = $this->description;
            $request->timePosted = date('Y-m-d H:i:s');
            if($request->save()){
                return isset($this->orderId) ? $this->orderId : true;
            }
        }
        return false;
    }

    public function countUserOrdersRequests($userId = null){
        $orders = Orders::find()
            ->where(['userId' => isset($userId) ? $userId : \Yii::$app->user->identity->id])
            ->all();
        $ordersReqNum = array();
        if(isset($orders)){
            foreach($orders as $order){
                $ordersReqNum[$order->id] = Order_requests::find()
                    ->where(['order_id' => $order->id])
                    ->andWhere(['status' => null])
                    ->count();
            }
        }
        return $ordersReqNum;
    }

    public function getRequestById($requestId){
        $request = Order_requests::find()
            ->where(['id' => $requestId])
            ->andWhere(['status' => null])
            ->one();
        return $request;
    }

    public function confirmReverseRequest($orderId){
        $order_usr = Orders_users::find()
            ->where(['order_id' => $orderId])
            ->andWhere(['status' => null])
            ->one();
        if(isset($order_usr)){
            $order_usr->status = 1;
            if($order_usr->save()){
                return true;
            }
        }
        return false;
    }
    public function rejectReverseRequest($orderId){
        $order_usr = Orders_users::find()
            ->where(['order_id' => $orderId])
            ->andWhere(['status' => null])
            ->one();
        if(isset($order_usr)){
            $order_usr->status = 0;
            if($order_usr->save()){
                return true;
            }
        }
        return false;
    }

    public function getRequestsByOrderId($orderId){
        $queryRequest = Order_requests::find()->where(['order_id' => $orderId])->orderBy(['status' => SORT_ASC])->all();
        $requests = array();
        foreach($queryRequest as $req){
            $request = new Request();
            $request->id = $req->id;
            $request->orderId = $req->order_id;
            $request->userId = $req->user_id;
            $request->login = User::getLoginByUserid($req->user_id);
            $request->description = $req->description;
            $request->status = $req->status;
            array_push($requests, $request);
        }
        return $requests;
    }

    public function confirmRequest($requestId){
        $request = $this->getRequestById($requestId);
        $orderId = $request->order_id;
        if($request->status != 'accept'){
            $request->status = 'accept';
            if($request->save()){
                $confRequest = new Orders_users();
                $confRequest->user_id = $request->user_id;
                $confRequest->order_id = $request->order_id;
                if($confRequest->save()){
                    $requests = Order_requests::find()
                        ->where(['order_id' => $orderId])
                        ->andWhere(['status' => null])
                        ->all();
                    if(isset($requests)){
                        foreach($requests as $req){
                            $req->status = "deny";
                            $req->save();
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function isRequestConfirmed($orderId, $userId = null){
        $conf = Orders_users::find()->where(['order_id' => $orderId]);
        if(!Order::isUsersOrder($orderId)){
            $conf = $conf->andWhere(['user_id' => isset($userId) ? $userId : \Yii::$app->user->identity->id]);
        }
            $conf = $conf->andWhere(['status' => null])->exists();
        return $conf;
    }

    public function isReverseRequestSend($orderId){
        if(Order::isOrderInProcess($orderId)){
            $RReq = Order_requests::find()
                ->where(['order_id' => $orderId])
                ->andWhere(['status' => null])
                ->exists();
            return $RReq;
        }
        return false;
    }

    public function isReverseRequestConfirmed($orderId, $userId = null){
        $RReq = Orders_users::find()->where(['order_id' => $orderId]);
        if(isset($userId)){
            $RReq = $RReq->andWhere(['user_id' => $userId]);
        }
            $RReq = $RReq->andWhere(['status' => 1])->exists();
        return $RReq;
    }

    public function denyRequest($requestId){
        $request = $this->getRequestById($requestId);
        if($request->status != 'deny'){
            $request->status = 'deny';
            if($request->save()){
                return true;
            }
        }
        return false;
    }

    public static function hasUserRequest($orderId, $userId = null){
        $perm = Order_requests::find()->where(['order_id' => $orderId])
            ->andWhere(['user_id' => isset($userId) ? $userId : \Yii::$app->user->identity->id])
            ->exists();
        return $perm;
    }
}