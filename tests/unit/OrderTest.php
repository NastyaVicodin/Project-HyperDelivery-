<?php


class OrderTest extends \Codeception\Test\Unit
{
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
    public function testAddOrder()
    {
        if($order->load($this->fixture) && $order->validate()){
            assertEquals(true, $order->addOrder());
        }

    }

    public function testcheckOrder()
    {
        assertEquals(true, $order->find()->where(['title' => 'Lorem ipsum Lorem ipsumLorem ipsumLorem ipsumLorem ipsum'])->exists());
    }

    public function testAddItems()
    {
        if($order->addItems($items)){
            $items = explode($fixture['items']);
            $iRealNum = \app\models\Items::find()->where(['order_id' => $i])->count();
            $iNum = 0;
            foreach($items as $i){
                $iNum += \app\models\Items::find()->where(['description' => $i])->andWhere(['order_id' => $fixture['orderId']])->exist();
            }
            assertEquals($iRealNum, $iNum);
        }
    }

}