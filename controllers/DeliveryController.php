<?php

namespace app\controllers;

use app\models\Comment;
use app\models\DeliveryOrders;
use app\models\Order;
use app\models\Orders;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\Registration;
use yii\web\NotFoundHttpException;
use yii\web\RegistrationFailException;
use yii\web\User;

class DeliveryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['registration'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('?r=orders%2Factive_orders');
    }


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Welcome '.Yii::$app->user->identity->login.'!');
            return $this->redirect(Yii::$app->user->returnUrl);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionCity_change($city){
        if(\app\models\User::cityChange(Yii::$app->user->identity->id, $city)){
            Yii::$app->user->identity->city = $city;
            return $this->redirect(Yii::$app->user->returnUrl);
        }
    }

    public function actionRegistration(){
        $model = new Registration();

        if($model->load(Yii::$app->request->post()) && $model->registerUser()){
            Yii::$app->session->setFlash('success', 'Successfull registration!');
            return $this->redirect(Url::to('login'));
        }else{
            Yii::$app->session->setFlash('error', 'Registration fail!');
        }
        return $this->render('registration', ['model' => $model]);
    }

    public function actionRegistrationConfirm($regCode){
        $model = new Registration();
        if($model->registrationConfirm($regCode)){
            $this->goHome();
        }else{
            throw new RegistrationFailException("Registration went wrong! Try again!");
        }

    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->user->returnUrl);
    }

    public function actionRegistration_confirm($regCode){
        $model = new Registration();
        if($model->registrationConfirm($regCode)){
            Yii::$app->session->setFlash('success', 'Registration confirmed!');
            return $this->redirect(Url::to('login'));
        }
        else{
            Yii::$app->session->setFlash('error', 'Registration confirmation fail!');
            return $this->redirect(Url::to('login'));
        }
    }

    public function actionProfile($id){
        $userModel = new \app\models\User();
        $user = $userModel->findIdentity($id);
        if($user){
            $commentModel = new Comment();
            $userEmplComments = $commentModel->getUserEmplComments($id);
            $userCustComments = $commentModel->getUserCustComments($id);
            return $this->render('profile', [
                'user' => $user,
                'userEmplComm' => $userEmplComments,
                'userCustComm' => $userCustComments,
                'countUserOrd' => Order::countUserDoneOrders($id),
                'countEmplOrd' => Order::countEmplUserOrders($id)
            ]);
        }else{
            throw new UserNotFoundException("User with this id doesnt exest!");
        }
    }

    public function actionMy_profile(){
        $deliveryModel = new DeliveryOrders();
        $commentModel = new Comment();
        $userId = Yii::$app->user->identity->id;
        $doneOrders = $deliveryModel->getHistoryOrders();
        $userOrders = $deliveryModel->getUserHistoryOrders();
        $userEmplComments = $commentModel->getUserEmplComments($userId);
        $userCustComments = $commentModel->getUserCustComments($userId);
        $sendEmplComments = $commentModel->getSendEmplComments($userId);
        $sendCustComments = $commentModel->getSendCustComment($userId);
        return $this->render('myProfile.php', [
                'doneOrders' => $doneOrders,
                'userEmplComm' => $userEmplComments,
                'userCustComm' => $userCustComments,
                'sendEmplComm' => $sendEmplComments,
                'sendCustComm' => $sendCustComments,
                'userOrders' => $userOrders,
                'countUserOrd' => Order::countUserDoneOrders(Yii::$app->user->identity->id),
                'countEmplOrd' => Order::countEmplUserOrders(Yii::$app->user->identity->id),
                'deliveryModel' => $model
            ]);
    }
    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

}