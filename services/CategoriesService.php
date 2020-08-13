<?php
declare(strict_types=1);

namespace app\services;

use app\models\NewsList;
use app\repositories\CategoriesRepository;
use app\repositories\NewsRepository;

class CategoriesService
{

    /**
     * @return array
     */
    public function getList(): array
    {
        $data = [
            'success' => true,
            'items'   => (new CategoriesRepository())->getTitleSortedList(),
        ];

        return $data;
    }

}