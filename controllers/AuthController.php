<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\AuthService;

class AuthController extends ApiControllerAbstract
{
    /**
     * @inheritDoc
     */
    protected function actionsMethods(): array
    {
        return [
            'index'  => ['get'],
            'login'  => ['post'],
            'logout' => ['post'],
        ];
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
