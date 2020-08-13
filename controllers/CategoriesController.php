<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\CategoriesService;
use app\services\NewsService;

class CategoriesController extends ApiControllerAbstract
{
    /**
     * @inheritDoc
     */
    protected function actionsMethods(): array
    {
        return [
            'index' => ['get'],
        ];
    }

    public function actionIndex()
    {
        return (new CategoriesService())->getList();
    }

}
