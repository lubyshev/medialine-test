<?php
declare(strict_types=1);

namespace app\services;

use app\repositories\NewsRepository;

class NewsService
{
    private const NEWS_PER_PAGE = 3;
    private const DEFAULT_ORDER = [
        'createdAt' => SORT_DESC,
    ];

    public function getList()
    {
        $page = (int)\Yii::$app->request->get('page');
        !$page && $page = 1;
        $provider = (new NewsRepository())->getListDataProvider(
            $page,
            self::NEWS_PER_PAGE,
            self::DEFAULT_ORDER
        );
        $provider->prepare();
        $data = [
            'success'   => true,
            'items'     => null,
            'page'      => $provider->pagination->page + 1,
            'pageCount' => $provider->pagination->pageCount,
        ];
        foreach ($provider->getModels() as $item) {
            $data['items'][$item->id] = $item;
        }

        return $data;
    }

}