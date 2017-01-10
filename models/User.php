<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public function setPassword($password){

        $this->userPassword = sha1($password);
    }

    public function validatePassword($password){
        return ($this->userPassword === sha1($password));//sha1
    }
    //--------------------------------------------------------------------
    public static function findIdentity($id){
        return self::findOne($id);
    }
    public function getId(){
        return $this->id;
    }
    public static function findIdentityByAccessToken($token, $type = null){}

    public function getAuthKey(){}

    public function validateAuthKey($authKey){}
//---------------------------------------------------------------------------------------------

    public function getLevel(){
        return $this->userLevel;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function getUserName(){
        return $this->userName;
    }



}
