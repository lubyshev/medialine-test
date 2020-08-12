<?php
declare(strict_types=1);

namespace app\controllers;

use yii\base\Controller;

abstract class ApiControllerAbstract extends Controller
{
    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }
}
