<?php
namespace app\models;

use yii\base\Model;

class Signup extends Model
{
    public $id;
    public $userName;
    public $userPassword;

    public function rules(){
        return [
            [['userName', 'userPassword'], 'required' ],
            ['userName', 'unique', 'targetClass' => 'app\models\User' ],
            ['userName', 'string', 'length' => [3, 25]],
            ['userName', 'match', 'pattern' =>  '/^[a-zA-Z0-9]+$/ui'],
            ['userPassword', 'string', 'length' => [3, 50]],
            ['userPassword', 'match', 'pattern' =>  '/^[a-zA-Z0-9]+$/ui'],
        ];

    }

    //-------------------------------------------------------------------------------------------------------
    public function attributeLabels()
    {
        return [
            'id' => '',
            'userName' => 'Логин',
            'userPassword' => 'Пароль',
        ];
    }
    public function signup(){
        $user = new User();
        $user->userName = $this->userName;
        $user->setPassword($this->userPassword);
        return $user->save();

    }


}