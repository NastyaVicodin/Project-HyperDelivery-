<?php


class CommentTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    public function setUpBeforeClass(){
        $orderId = '1';
        $userId = \app\models\User::find()->select('id')->where(['login' => 'admin']);
        $comm = new \app\models\Comment();
    }

    public function tearDownAfterClass(){
        \app\models\Comments::find()->where(['order_id' => $orderId])->andWhere([['user_id' => $userId]])->deleteAll();
    }

    public function setUp()
    {

        $fixture = [
            'orderId' => $orderId,
            'userId' => $userId,
            'text' => 'Lorem ipsum',
            'rating' => 9;
        ];
    }

    public function tearDown()
    {
    }

    // tests
    public function testAddComment($fixture['orderId'])
    {
        if($comm->load($fixture) && $comm->validate()){
            $comm->addComment($fixture['userId']);
            assertEquals($comm->hasUserComment($fixture['orderId'], $fixture['userId']));
        }
    }

    public function testRatingEmployeeCalculation()
    {
        $rat = $fixture['rating'];
        $userInitialRating = \app\models\User::find()->select('employee_rating')->where(['id' => $fixture['userId']])->one();
        if($comm->calcRating($fixture['userId'], 'employee')){
            $userRating = \app\models\User::find()->select('employee_rating')->where(['id' => $fixture['userId']])->one();
            assertEquals(isset($userInitialRating) ?
                $userRating : 0, $rat);
        }
    }

    public function testRatingCustomerCalculation()
    {
        $rat = $fixture['rating'];
        $userInitialRating = \app\models\User::find()->select('customer_rating')->where(['id' => $fixture['userId']])->one();
        if($comm->calcRating($fixture['userId'], 'customer')){
            $userRating = \app\models\User::find()->select('customer_rating')->where(['id' => $fixture['userId']])->one();
            assertEquals(isset($userInitialRating) ?
                $userRating : 0, $rat);
        }
    }
}