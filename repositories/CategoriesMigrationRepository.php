<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\Category;

class CategoriesMigrationRepository
{
    private static function tableName(): string
    {
        return Category::tableName();
    }

    public function createRootCategory(array $category): Category
    {
        unset($category['items']);
        $category['subtree']  = self::getNewSubtree();
        $category['level']    = 1;
        $category['left']     = 1;
        $category['right']    = 2;
        $category['parentId'] = null;

        $model = (new Category())->initCategory($category);
        $model->save();

        return $model;
    }

    public function addChildCategory(Category $parent, array $category): Category
    {
        unset($category['items']);
        $parent->refresh();
        $this->createChildPlace($parent);
        $parent->refresh();
        $category['subtree']  = $parent->subtree;
        $category['level']    = $parent->level + 1;
        $category['left']     = $parent->right - 2;
        $category['right']    = $parent->right - 1;
        $category['parentId'] = $parent->id;

        $model = (new Category())->initCategory($category);
        $model->save();

        return $model;
    }

    /** @noinspection SqlResolve */
    private static function getNewSubtree(): int
    {
        $table = self::tableName();

        return (int)\Yii::$app->db->createCommand(
            "SELECT coalesce(MAX(`subtree`),0) + 1 as subtree FROM {$table}"
        )->queryScalar();
    }

    private function createChildPlace(Category $parent): void
    {
        do {
            $parent->right += 2;
            $parent->save();
            $parent = $parent->parent();
        } while ($parent);
    }

}
