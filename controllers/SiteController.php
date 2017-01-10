<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Signup;
use app\models\Login;


class SiteController extends Controller
{

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }
//-----------------------------------------------------------------------------------------------------------------
//самостоятельная регистрация нового пользователя
  function actionSignup(){
        $model = new Signup();
        if (isset($_POST['Signup'])){
            $model->attributes = Yii::$app->request->post('Signup');
            if ($model->validate() && $model->signup()){
                return $this->goHome();
            }
        }
        return $this->render('signup',['model' => $model]);
        }
//-----------------------------------------------------------------------------------------------------------------
// вход зарегистрированного пользователя
    function actionLogin(){
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }

        $login_model = new Login();
        if(Yii::$app->request->post('Login')){
            $login_model->attributes = Yii::$app->request->post('Login');
            if ($login_model->validate()){
                Yii::$app->user->login($login_model->getUser());
                return $this->goHome();
            }
        }
        return $this->render('login',['login_model' => $login_model ]);
    }

}
