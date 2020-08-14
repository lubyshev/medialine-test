<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\Category;
use app\models\News;
use app\models\NewsList;
use yii\data\ActiveDataProvider;

class NewsRepository
{
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

    public function getNewsListDataProvider(int $limit, array $order): ActiveDataProvider
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

    public function getCategoryNewsListDataProvider(
        Category $category,
        int $limit,
        array $order
    ): ActiveDataProvider {
        $newsIds = Category::find()
            ->select('cn.`newsId`')
            ->distinct()
            ->from('{{%news_categories}} cn')
            ->leftJoin(
                '{{%categories}} c',
                'c.`id` = cn.`categoryId`'
            )
            ->where(['>=', 'c.`left`', $category->left])
            ->andWhere(['<=', 'c.`right`', $category->right])
            ->andWhere(['=', 'c.`subtree`', $category->subtree])
            ->column();

        return new ActiveDataProvider([
            'query'      => NewsList::find()->where(['in', '`id`', $newsIds]),
            'pagination' => [
                'pageSize' => $limit,
            ],
            'sort'       => [
                'defaultOrder' => $order,
            ],
        ]);
    }

    public function getCategoryNewsCount(Category $category): int
    {
        return (int)Category::find()
            ->select('cn.`newsId`')
            ->distinct()
            ->from('{{%news_categories}} cn')
            ->leftJoin(
                '{{%categories}} c',
                'c.`id` = cn.`categoryId`'
            )
            ->where(['>=', 'c.`left`', $category->left])
            ->andWhere(['<=', 'c.`right`', $category->right])
            ->andWhere(['=', 'c.`subtree`', $category->subtree])
            ->count();
    }

}
