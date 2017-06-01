<?php
namespace app\controllers;


use app\models\Comment;
use app\models\Orders;
use Yii;

use yii\base\InvalidValueException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use app\models\DeliveryOrders;
use app\models\Order;
use app\models\Request;
use yii\web\FailedRequestConfirmException;
use yii\web\NotFoundHttpException;
use yii\web\OrderNotFoundException;

class OrdersController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['my_orders', 'add_order'],
                'rules' => [
                    [

                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    public function actionIndex(){
        return $this->redirect('/orders/activeOrders');
    }

    public function actionActive_orders($orderBy = 'new', $date = null, $time = null, $price = null, $search = null){
            $model = new DeliveryOrders();
            $orders = $model->getOrders($orderBy, $date, $time, $price, $search);

        return $this->render('activeOrders', [
            'orders' => $orders,
            'model' => $model,
            'filters' => [
                'orderBy' => $orderBy,
                'date' => $date,
                'time' => $time,
                'price' => $price,
                'search' => $search
            ]
        ]);
    }

    public function actionOrder($orderId){
        $model = new Order();
        $requestModel = new Request();
        $order = $model->getOrderById($orderId);
        $isOrderInProcess = Order::isOrderInProcess($orderId);
        if(!Yii::$app->user->isGuest){
            $isRequestSend = Request::hasUserRequest($orderId);
            $isUsersOrder = Order::isUsersOrder($orderId);
            if($isRequestSend || $isUsersOrder){
                $isRequestConfirmed = $requestModel->isRequestConfirmed($orderId);
                if($isRequestConfirmed){
                    $isRRequestSend = $requestModel->isReverseRequestSend($orderId);
                    if($isRRequestSend){
                        $isRRequestConf = $requestModel->isReverseRequestConfirmed($orderId);
                    }
                }
            }
            if(!Comment::hasUserComment($orderId)){
                $commentModel = new Comment();
            }
            if($isUsersOrder){
                $requests = $requestModel->getRequestsByOrderId($orderId);
            }
        }
        if($order){
            return $this->render('order.php', [
            'order' => $order,
            'model' => $model,
            'commentModel' => isset($commentModel) ? $commentModel : null,
            'requestModel' => $requestModel,
            'isRequestSend' => $isRequestSend,
            'isUsersOrder' => $isUsersOrder,
            'requests' => $requests,
            'isOrderInProc' => $isOrderInProcess,
            'isReqConfirmed' => $isRequestConfirmed,
            'isRRequestSend' => $isRRequestSend,
            'isRRequestConf' => $isRRequestConf
            ]);
        }else{
            throw new OrderNotFoundException('Order with this id doesnt exist!');
        }
    }

    public function actionAdd_comment($orderId){
        $model = new Comment();
        if((Order::isUsersOrder($orderId) && (new Request)->isReverseRequestConfirmed($orderId)) ||
            (new Request)->isReverseRequestConfirmed($orderId, Yii::$app->user->identity->id)){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    if($model->addComment($orderId, Yii::$app->user->identity->id)){
                        Yii::$app->session->setFlash('Success', 'Comment successfully send!');
                        return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
                    }
                }
        }
        else{
            Yii::$app->session->setFlash('Error', 'You havent permission!');
            return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
        }
    }

    public function actionFulfillment_request($orderId){
        $model = new Request();
        if(Order::isOrderInProcess($orderId) && $model->isRequestConfirmed($orderId)){
            if($model->addRequest($orderId)){
                Yii::$app->session->setFlash('Success', 'Fullfillment request successfuly send!');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }
        }
        else{
            Yii::$app->session->setFlash('Error', 'You havent permission!');
            return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
        }
    }

    public function actionFulfillment_confirm($orderId){
        $model = new Request();
        if(Order::isUsersOrder($orderId) && $model->isRequestConfirmed($orderId)){
            if($model->confirmReverseRequest($orderId)){
                Yii::$app->session->setFlash('Success', 'Order successfuly confirmed!');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }else{
                throw new FailedRequestConfirmException("Something went wrong in request confirmation!");
            }
        }
        else{
            throw new BadRequestHttpException;
        }
    }

    public function actionRequest(){
        $model = new Request();
        if(!Yii::$app->user->isGuest){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    $orderId = $model->addRequest();
                    if($orderId){
                        Yii::$app->session->setFlash('success', 'Request successfuly send!');
                        return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
                    }
                }
                else{
                    Yii::$app->session->setFlash('Success', 'Request failed to send!');
                    return $this->redirect(Url::to(['delivery/login']));
                }
        }
        else{
            return $this->redirect(Url::to(['/delivery/login']));
        }
    }

    public function actionConfirm_request($orderId, $requestId){
        if(Order::isUsersOrder($orderId) && !Order::isOrderInProcess($orderId)){
            $model = new Request();
            if($model->confirmRequest($requestId)){
                Yii::$app->session->setFlash('Success', 'Request successfuly confirmed');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }
            else{
                Yii::$app->session->setFlash('Error', 'Request was already send!');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }
        }else{
            Yii::$app->session->setFlash('Error', 'Permission denied!');
            return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
        }
    }

    public function actionDeny_request($orderId, $requestId){
        if(Order::isUsersOrder($orderId) && !Order::isOrderInProcess($orderId)){
            $model = new Request;
            if($model->denyRequest($requestId)){
                Yii::$app->session->setFlash('Success', 'Request successfuly denied');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }else{
                Yii::$app->session->setFlash('Error', 'Request was already denied!');
                return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
            }
        Yii::$app->session->setFlash('Error', 'Permission denied!');
        return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
        }
    }

    public function actionFulfillment_reject($orderId){
        if(Order::isUsersOrder($orderId) && Order::isOrderInProcess($orderId)){
            $model = new Request;
            if($model->isReverseRequestSend($orderId)){
                if($model->rejectReverseRequest($orderId)){
                    Yii::$app->session->setFlash('success', 'Order finished as unsuccessfull!');
                    return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
                }else{
                    Yii::$app->session->setFlash('Error', 'Something went wrong in order rejection!');
                    return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
                }
            }
        Yii::$app->session->setFlash('Error', 'Permission denied!');
        return $this->redirect(Url::to(['orders/order', 'orderId' => $orderId]));
        }
    }

    public function actionFilter_orders(){


            $model = new DeliveryOrders();

            $model->load(Yii::$app->request->post());

                $orders = $model->getOrders();


                     return $this->render('activeOrders', [
                         'orders' => $orders,
                         'model' => $model]);



    }

    public function actionMy_orders(){
        if(!Yii::$app->user->isGuest){
            $model = new DeliveryOrders();
            $requestOrders = $model->getUserRequests();
            $ordersInProc = $model->getUserOrdersInProcess(Yii::$app->user->identity->id);
            $myOrders = $model->getUserOrders();
            $ordersReqNum = (new Request())->countUserOrdersRequests();
            return $this->render('my_orders.php', [
                'model' => $model,
                'requestOrders' => $requestOrders,
                'myOrders' => $myOrders,
                'ordersInProc' => $ordersInProc,
                'ordersReqNum' => $ordersReqNum
            ]);
        }else{
            return $this->redirect(Url::to(['/delivery/login']));
        }
    }

   public function actionDelete($orderId){
       $model = new Order();
        if(Order::isUsersOrder($orderId)){
            if(!Order::isOrderInProcess($orderId)){
                if($model->deleteOrderById($orderId)){
                    Yii::$app->session->setFlash('orderDelete', 'Order deleted successfully!');
                    return $this->redirect(Url::to(['/orders/my_orders']));
                }
            }else{
                Yii::$app->session->setFlash('error', 'You cant delete order in process!!');
                return $this->redirect(Url::to(['/orders/my_orders']));
            }
        }
       else{
           Yii::$app->session->setFlash('error', 'You havent permisson!');
           return $this->redirect(Url::to(['/orders/my_orders']));
       }
    }

    public function actionAdd_order(){
        if(!Yii::$app->user->isGuest){
            $model = new Order();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->addOrder()){
                    return $this->redirect(Url::to(['/orders/my_orders']));
                }
            }
            return $this->render('addOrder.php', [
                'model' => $model,
            ]);
        }
        else{
            return $this->redirect(Url::to(['/delivery/login']));
        }
    }

    public function actionEdit_order($orderId){
    if(!Yii::$app->user->isGuest){
        $model = new Order();
        if(isset($orderId)){
            if(Order::isUsersOrder($orderId)){
                $order = $model->getOrderById($orderId);
                $itemsStr = $model->getItemsByOrderId($orderId);
                return $this->render('editOrder', [
                    'model' => $model,
                    'order' => isset($order) ? $order : null,
                    'itemsStr' => isset($itemsStr) ? $itemsStr : null
                ]);
            }
        }
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if($model->editOrder($orderId)){
                Yii::$app->session->setFlash('success', 'Your order successfuly changed!');
                return $this->redirect(Url::to(['/orders/my_orders']));
            }
        }

    }
   }

    public function actionDelete_request($orderId){
        $model = new Request();
        if(!Yii::$app->user->isGuest && $model->deleteRequest($orderId)){
            Yii::$app->session->setFlash('success', 'Request successfuly deleted!');
        }
        return $this->redirect(Url::to(['/orders/my_orders']));
    }
}