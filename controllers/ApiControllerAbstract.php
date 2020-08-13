<?php
declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;

abstract class ApiControllerAbstract extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => $this->actionsMethods(),
            ],
        ];
    }

    public function afterAction($action, $result)
    {
        $result['commonRules'] = [
            'loggedIn' => !\Yii::$app->user->isGuest,
        ];

        return parent::afterAction($action, $result);
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * Returns allowed methods for each action of controller.
     *
     * Example:
     *
     * return [
     *      'index' => ['get'],
     *      'create' => ['get','post'],
     *      'unlink' => ['delete'],
     * ];
     *
     * @return \string[][]
     */
    abstract protected function actionsMethods(): array;

}
