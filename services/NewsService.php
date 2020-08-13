<?php
declare(strict_types=1);

namespace app\services;

use app\models\Category;
use app\models\NewsList;
use app\repositories\NewsRepository;
use \yii\data\ActiveDataProvider;

class NewsService
{
    private const NEWS_PER_PAGE = 3;
    private const DEFAULT_ORDER = [
        'createdAt' => SORT_DESC,
    ];

    /**
     * @return array
     */
    public function getList(): array
    {
        $provider = (new NewsRepository())->getNewsListDataProvider(
            self::NEWS_PER_PAGE,
            self::DEFAULT_ORDER
        );

        return $this->getNewsListFromDataProvider($provider);
    }

    public function getCategoryList(int $categoryId): array
    {
        $provider              = (new NewsRepository())->getCategoryNewsListDataProvider(
            $categoryId,
            self::NEWS_PER_PAGE,
            self::DEFAULT_ORDER
        );
        $data                  = $this->getNewsListFromDataProvider($provider);
        $data['categoryTitle'] = (Category::findOne(['id' => $categoryId]))->title;

        return $data;
    }

    private function getNewsListFromDataProvider(ActiveDataProvider $provider): array
    {
        $provider->prepare();
        $data          = [
            'success'   => true,
            'page'      => $provider->pagination->page + 1,
            'pageCount' => $provider->pagination->pageCount,
        ];
        $data['items'] = $this->getNewsListItems($provider);

        return $data;
    }

    private function getNewsListItems(ActiveDataProvider $provider): ?array
    {
        $result = [];
        foreach ($provider->getModels() as $item) {
            /** @var NewsList $item */
            $result[] = $item;
            [
                'id'          => $item->id,
                'date'        => $item->date,
                'userName'    => $item->userName,
                'breadcrumbs' => $item->breadcrumbs,
                'title'       => $item->title,
                'content'     => $item->content,
            ];
        }

        return empty($result) ? null : $result;
    }

}
