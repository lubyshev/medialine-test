<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\NewsService;

class NewsController extends ApiControllerAbstract
{
    /**
     * @inheritDoc
     */
    protected function actionsMethods(): array
    {
        return [
            'index' => ['get'],
            'category' => ['get'],
        ];
    }

    public function actionIndex()
    {
        return (new NewsService())->getList();
    }

    public function actionCategory(int $id)
    {
        return (new NewsService())->getCategoryList($id);
    }

}
