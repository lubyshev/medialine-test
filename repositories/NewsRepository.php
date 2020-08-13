<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\News;
use app\models\NewsList;
use yii\data\ActiveDataProvider;

class NewsRepository
{
    public function getListDataProvider(int $limit, array $order): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query'      => NewsList::find(),
            'pagination' => [
                'pageSize' => $limit,
            ],
            'sort'       => [
                'defaultOrder' => $order,
            ],
        ]);
    }

    public function getBreadcrumbs(News $model): string
    {
        $result     = [];
        $repo       = new CategoriesRepository();
        $categories = $repo->getNewsCategories($model)->all();
        foreach ($categories as $category) {
            $result[] = \Yii::$app->view->render('/news/breadcrumbs', [
                'parents' => $repo->getCategoryParents($category)->all(),
                'item'    => $category,
            ]);
        }

        return implode("\n", $result);
    }

}