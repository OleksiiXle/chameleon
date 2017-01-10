<?php
/**
 * Created by PhpStorm.
 * User: Oleksii
 * Date: 17.11.2016
 * Time: 14:46
 */

namespace app\models;


use yii\base\Model;


class Login extends Model
{
    public $userName;
    public $userPassword;

    public function rules(){
        return [
            [['userName', 'userPassword'], 'required'],
            ['userPassword', 'validatePassword']
        ];

    }
    public function validatePassword($attribute,$params){
        if (!$this->hasErrors()){
            $user = User::findOne(['userName' => $this->userName]);
            if ((!$user) || !$user->validatePassword($this->userPassword)) {
                $this->addError($attribute, ' Wrong password or UserName');
            }
        }
    }

    public function getUser(){
        return User::findOne(['userName' => $this->userName]);
    }


}