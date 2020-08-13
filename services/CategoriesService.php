<?php
declare(strict_types=1);

namespace app\services;

use app\repositories\CategoriesRepository;

class CategoriesService
{

    /**
     * @return array
     */
    public function getList(): array
    {
        return [
            'success' => true,
            'items'   => (new CategoriesRepository())->getTitleSortedList(),
        ];
    }

}