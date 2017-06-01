<?php
namespace app\models;

use yii\base\Model;

class Comment extends Model{
    public $id;
    public $creatorId;
    public $text;
    public $timePosted;
    public $rating;

    public function rules(){
        return [
            ['text' , 'required'],
            ['text', 'string', 'length' => [10, 1000]],
            ['rating', 'integer', 'min' => '0', 'max' => '9']
        ];
    }

    public function addComment($orderId, $userId = null){
        $order = Orders_users::find()->where(['order_id' => $orderId])->one();
        $comment = new Comments();
        $comment->timePosted = date('Y-m-d H:i:s');
        $comment->text = $this->text;
        $comment->creator_id = isset($userId) ? $userId : \Yii::$app->user->identity->id;
        $comment->user_id = ($order->user_id == \Yii::$app->user->identity->id) ? $order->order->userId : $order->user_id;
        $comment->order_id = $orderId;
        $comment->rating = isset($this->rating) ? $this->rating+1 : null;
        if($comment->save()){
            if($order->user_id == \Yii::$app->user->identity->id) {
                $this->calcRating($order->order->userId, 'customer');
                return true;
            }else{
                $this->calcRating($order->user_id, 'employee');
                return true;
            }
        }
        return false;
    }

    public function calcRating($userId, $destiny){
        $user = User::find()->where(['id' => $userId])->one();
        $marks = Comments::find()->where(['user_id' => $userId])->all();
        $rating = 0;
        $feedbNum = 0;
        foreach($marks as $mark){
            $isUserExec = Orders_users::find()->where(['user_id' => $mark->user_id])
                ->andWhere(['order_id' => $mark->order_id])
                ->exists();

            if($destiny == 'customer' && !$isUserExec){
                $rating += $mark->rating;
                $feedbNum++;
            }elseif($destiny == 'employee' && $isUserExec){
                $rating += $mark->rating;
                $feedbNum++;
            }
        }
        if($destiny == 'customer' && $rating){
            $user->customer_rating = $rating/$feedbNum;
            if($user->save()){
                return true;
            }
        }
        elseif($destiny == 'emoloyee' && $rating){
            $user->employee_rating = $rating/$feedbNum;
            if($user->save){
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function getUserEmplComments($userId){
        $commentsQuery = Comments::find()
            ->where(['user_id' => $userId])->all();
        $comments = array();
        if(isset($commentsQuery)){
            foreach($commentsQuery as $comm){
                $ord = Orders_users::find()->where(['order_id' => $comm->order_id])->one();
                if($comm->user_id == $ord->user_id){
                    array_push($comments, $comm);
                }
            }
        }
        return $comments;
    }

    public function getUserCustComments($userId){
        $commentsQuery = Comments::find()
            ->where(['user_id' => $userId])->all();
            $comments = array();
        if(isset($commentsQuery)){
            foreach($commentsQuery as $comm){
                if($comm->order->userId == $comm->user_id){
                    array_push($comments, $comm);
                }
            }
        }
        return $comments;
    }

    public function getSendEmplComments($userId){
        if($userId == \Yii::$app->user->identity->id){
            $commentsQuery = Comments::find()
                ->where(['creator_id' => $userId])->all();
            $comments = array();
            if(isset($commentsQuery)){
                foreach($commentsQuery as $comm){
                    $ord = Orders_users::find()->where(['order_id' => $comm->order_id])->one();
                    if(isset($ord)){
                        if($comm->user_id == $ord->user_id){
                            array_push($comments, $comm);
                        }
                    }
                }
            }
            return $comments;
        }
    }

    public function editComment($id){
        $comment = Comments::findOne($id);
        if($comment->creator_id == \Yii::$app->user->identity->id){
            if($this->validate()){

            }
        }
    }

    public function getSendCustComment($userId){
        if($userId == \Yii::$app->user->identity->id){
            $commentsQuery = Comments::find()
                ->where(['creator_id' => $userId])->all();
            $comments = array();
            if(isset($commentsQuery)){
                foreach($commentsQuery as $comm){
                    if($comm->order->userId == $comm->user_id){
                        array_push($comments, $comm);
                    }
                }
            }
            return $comments;
        }
    }

    public static function hasUserComment($orderId, $userId = null){
        $UsCom = Comments::find()->where(['order_id' => $orderId])
            ->andWhere(['creator_id' => isset($userId) ? $userId : \Yii::$app->user->identity->id])
            ->exists();
        return $UsCom;
    }
}