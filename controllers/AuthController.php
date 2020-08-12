<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\AuthService;
use yii\base\Controller;

class AuthController extends Controller
{
    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return (new AuthService())->getAuthForm();
    }

    public function actionLogin()
    {
        return (new AuthService())->login();
    }

    public function actionLogout()
    {
        return (new AuthService())->logout();
    }

}
