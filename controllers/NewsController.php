<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\NewsService;

class NewsController extends ApiControllerAbstract
{

    public function actionIndex()
    {
        return (new NewsService())->getList();
    }

}
