<?php
declare(strict_types=1);

namespace app\services;

use app\models\User;

class AuthService
{
    public function getAuthForm(): array
    {
        return [
            'success' => true,
            'schema'  => 'form-dialog',
            'data'    => [
                'id'       => 'login_dialog',
                'fields'   => [
                    'login'    => '',
                    'password' => '',
                    'csrf'     => \Yii::$app->request->csrfToken,
                ],
                'methods'  => [
                    [
                        'name' => 'submit',
                        'code' => 'app.postLoginForm();',
                    ],
                    [
                        'name' => 'cancel',
                        'code' => 'app.closeDialog();',
                    ],
                ],
                'template' => \Yii::$app->view->render('/auth/loginDialog'),
            ],
        ];
    }

    public function login(): array
    {
        $params = \Yii::$app->request->post();
        $data   = [];
        $error  = null;
        if (\Yii::$app->request->validateCsrfToken($params['csrf'])) {
            $user = User::findByUsername($params['login']);
            if (!$user) {
                $error = true;
            } elseif ($user->password !== $params['password']) {
                $error = true;
            } else {
                \Yii::$app->user->login($user);
                $data = [
                    'success' => true,
                    'user'    => [
                        'id'    => $user->id,
                        'name'  => $user->username,
                        'token' => $user->accessToken,
                    ],
                ];
            }
            if ($error) {
                $error = [
                    'statusCode' => 403,
                    'message'    => 'Invalid Login or Password.',
                ];
            }
        } else {
            $error = [
                'statusCode' => 403,
                'message'    => 'Invalid CSRF Token',
            ];
        }
        if ($error) {
            \Yii::$app->response->statusCode = $error['statusCode'];
            $data                            = [
                'success' => false,
                'error'   => $error,
            ];
        }

        return $data;
    }

    public function logout(): array
    {
        \Yii::$app->user->logout();
        $data = [
            'success' => true,
        ];

        return $data;
    }

}
