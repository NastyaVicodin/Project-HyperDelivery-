<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public function getOrders(){
        return $this->hasMany(Orders::className(), ['userId' => 'id']);
    }

    public function getPostedComments(){
        return $this->hasMany(Comments::className(), ['creator_id' => 'id']);
    }

    public function getComments(){
        return $this->hasMany(Comments::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tablename(){
        return 'user';
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'email' => 'Email',
            'pass' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token'
        ];
    }
    public static function findIdentity($id){
        return static::findOne(['id' => $id]);;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return static::findOne(['auth_key' => $token]);
    }


    public function setPassword($password){
        $this->pass = \Yii::$app->security->generatePasswordHash($password);
        return $this->pass;
    }

    public function setAuthKey(){
        $this->auth_key = \Yii::$app->security->generateRandomString(32);
        return $this->auth_key;
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */

    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);

    }

    public static function getLoginByUserid($id){
        $user = User::find()->select('login')->where([
            'id' => $id
        ])->one();
        return $user->login;
    }

    public static function cityChange($id, $city){
        $user = User::findIdentity($id);
        $user->city = $city;
        if($user->save()){
            return true;
        }
        return false;
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    public function getCity(){
        return $this->city;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if(is_null($this->pass))
            return false;
        return \Yii::$app->security->validatePassword($password, $this->pass);
    }

    public function checkEmailConfirmation(){
        if(isset($this->reg_code)){
            return false;
        }
        return true;
    }
}
