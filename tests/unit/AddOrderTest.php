<?php

require(__DIR__ . '/../models/Order.php');

class RequestTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    public $fixture;
    public static function setUpBeforeClass(){
        $order = new Order();
    }

    public function setUp()
    {
        $this->fixture = [
            'title' => 'Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsum',
            'orderDate' => date('Y-m-d H:i:s'),
            'startHour' => '12',
            'startMinutes' => '25',
            'endHour' => '13',
            'endMinutes' => '30',
            'description' => 'Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsum
            Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsumLorem ipsum Lorem ',
            'price' => '123',
            'items' => 'asfasdas&dasdasdasd&dasdasdasd&asdas'
        ];
    }

    public function tearDown()
    {
        $order = \app\models\Orders::find()->where(['title' => 'Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsum'])->delete();
    }

    // tests
    public function testaddOrder()
    {
        if($order->load($this->fixture) && $order->validate()){
            assertEquals(true, $order->addOrder());
        }

    }

    public function testcheckOrder(){
        assertEquals(true, $order->find()->where(['title' => 'Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsum'])->exists());
    }
}