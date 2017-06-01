<?php


class RequestTestCest extends \Codeception\Test\Unit
{

    public function setUpBeforeClass(){
        $orderId = 1;
        $userId = \app\models\User::find()->select('id')->where(['login' => 'admin']);
        $request = new \app\models\Request();
    }

    public function tearDownAfterClass(){
        \app\models\Order_requests::find()->where(['order_id' => $orderId])->andWhere([['user_id' => $userId]])->deleteAll();
        \app\models\Orders_users::find()->where(['order_id' => $orderId])->andWhere([['user_id' => $userId]])->deleteAll();
    }

    public function setUp()
    {

        $fixture = [
            'orderId' => $orderId,
            'userId' => $userId,
            'desctiption' => 'Lorem ipsum',
            'timePosted' => date('Y-m-d H:i:s');
        ];
    }

    public function tearDown()
    {
    }

    public function testRequest()
    {
        if($request->load($fixture) && $request->validate()){
            $request->addRequest();
            assertEquals(true, \app\models\Request::hasUserRequest($fixture['orderId'], $fixture['userId']));
        }
    }

    public function testRequestConfirmation()
    {
        assertEquals(true, $request->confirmRequest($fixture['orderId']));
    }

    public function testCheckRequestConfirmed()
    {
        assertEquals(true, $request->isRequestConfirmed($fixture['orderId'], $fixture['userId']));
    }

    public function testReverseRequestAdd()
    {
        if($request->addRequest()){
            $reqNum = \app\models\Order_requests::find()
                ->where(['order_id' => $fixture['orderId']])
                ->andWhere(['user_id' => $fixture['userId']])
                ->andWhere(['status' => 'accept'])
                ->count();
            $reqNum += \app\models\Order_requests::find()
                ->where(['order_id' => $fixture['orderId']])
                ->andWhere(['user_id' => $fixture['userId']])
                ->andWhere(['status' => null])
                ->count();
        }
        assertEquals(2, $reqNum);
    }

    public function testReverseRequestConfirmation()
    {
        $request->confirmReverseRequest($fixture['orderId']);
        assertEquals(true, $request->isReverseRequestConfirmed($fixture['orderId'], $fixture['userId']));
    }

    public function testRequestDelete()
    {
        if($request->addRequest() && $request->hasUserRequest($fixture['orderId'], $fixture['userId'])){
            assertEquals(true, $request->deleteRequest($fixture['orderId']));
        }
    }

    public function testCheckRequestDelete()
    {
        assertEquals(false, \app\models\Order_requests::find()
            ->where(['order_id' => $fixture['orderId']])
            ->andWhere(['user_id' => $fixture['userId']])
            ->andWhere(['status' => null])
            ->exists());
    }
}
