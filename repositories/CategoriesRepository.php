<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\Category;
use app\models\News;
use yii\db\ActiveQuery;

class CategoriesRepository
{

    /**
     * @param News $model
     *
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getNewsCategories(News $model): ActiveQuery
    {
        return $model
            ->hasMany(Category::class, ['id' => 'categoryId'])
            ->viaTable('{{%news_categories}}', ['newsId' => 'id']);
    }

    /**
     * @param Category $category
     *
     * @return ActiveQuery
     */
    public function getCategoryParents(Category $category): ActiveQuery
    {
        return Category::find()
            ->where(['<', 'left', $category->left])
            ->andWhere(['>', 'right', $category->right])
            ->andWhere(['<', 'level', $category->level])
            ->andWhere(['=', 'subtree', $category->subtree])
            ->orderBy(['level' => SORT_ASC]);
    }

}
