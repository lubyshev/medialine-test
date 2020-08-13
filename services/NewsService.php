<?php
declare(strict_types=1);

namespace app\services;

use app\models\NewsList;
use app\repositories\NewsRepository;

class NewsService
{
    private const NEWS_PER_PAGE = 3;
    private const DEFAULT_ORDER = [
        'createdAt' => SORT_DESC,
    ];

    public function getList()
    {
        $provider = (new NewsRepository())->getListDataProvider(
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
            /** @var NewsList $item*/
            $data['items'][$item->id] = $item;
        }

        return $data;
    }

}