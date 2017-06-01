<?php
namespace app\models;

use yii\base\Model;
use app\models\User;
class Registration extends Model {
    public $login;
    public $password;
    public $email;
    public $name;
    public $lastname;
    public $birthYear;
    public $birthMonth;
    public $description;
    private $reg_code;
    public function rules(){
        return array(
            [['login', 'password', 'email', 'name', 'lastname', 'birthYear', 'birthMonth'], 'required'],
            ['email', 'email'],
            ['birthYear', 'date', 'format' => 'yyyy'],
            [['login', 'email'] , 'unique', 'targetClass' => 'app\models\User'],
            ['password', 'string', 'length' => [4, 24]],
            [['description'], 'safe'],
        );
    }

    public function sendRegistrationEmail($reg_code){
        if(\Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom('betelgeuse1920@gmail.com')
            ->setSubject('Registration')
            ->setTextBody('Confirm your registration by entering the link https://room217.herokuapp.com/web/delivery/registration_confirm?regCode='.$reg_code)
            ->send()){
                return true;
        }
        return false;
    }
    /**
     * Registrion new user.
     * This method serves as user registration with confirm mailer.
     *
     * @return boolean whether the registration form was validated successuly and mail was send.
     */
    public function registerUser(){
        if($this->validate()){
            $reg_code = \Yii::$app->security->generateRandomString(30);
            if($this->sendRegistrationEmail($reg_code)){
                $user = new \app\models\User();
                $user->login = $this->login;
                $user->setPassword($this->password);
                $user->email = $this->email;
                $user->name = $this->name;
                $user->lastname = $this->lastname;
                $user->birthday = $this->birthYear.'-'.$this->birthMonth;
                $user->auth_key = \Yii::$app->security->generateRandomString(30);
                $user->reg_code = $reg_code;
                $user->registration_date = date('Y-m-d H:i:s');
                $user->save();
                return true;
            }
        return false;
        }
    }

    public function registrationConfirm($regCode){
        $user = User::findOne(['reg_code' => $regCode]);
        if(isset($user)){
            $user->reg_code = null;
            if($user->save())
                return true;
        }
        return false;
    }


}