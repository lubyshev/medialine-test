<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\News;
use yii\data\ActiveDataProvider;

class NewsRepository
{
    public function getListDataProvider(int $page, int $limit, array $order): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => News::find(),
            'pagination' => [
                'pageSize' => $limit,
            ],
            'sort' => [
                'defaultOrder' => $order
            ],
        ]);
    }

}