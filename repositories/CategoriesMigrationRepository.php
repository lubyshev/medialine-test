<?php
declare(strict_types=1);

namespace app\repositories;

use app\models\Category;
use yii\db\Command;

class CategoriesMigrationRepository
{
    private static function tableName(): string
    {
        return Category::tableName();
    }

    public function createRootCategory(array $category): Category
    {
        unset($category['items']);
        $category['subtree'] = self::getNewSubtree();
        $category['level']   = 1;
        $category['left']    = 1;
        $category['right']   = 2;

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
        $category['subtree'] = $parent->subtree;
        $category['level']   = $parent->level + 1;
        $category['left']    = $parent->left + 1;
        $category['right']   = $parent->right - 1;

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

    private function getChildrenUpdateCommand(): Command
    {
        static $command;
        if (!$command) {
            $table   = self::tableName();
            $command = \Yii::$app->db->createCommand(
                "UPDATE {$table} SET"
                ." `left`=`left`+1,"
                ." `right`=`right`+1"
                ." WHERE `left`>:left AND `subtree`=:subtree"
            );
            $command->prepare();
        }

        return $command;
    }

    private function getParentsUpdateCommand(): Command
    {
        static $command;
        if (!$command) {
            $table   = self::tableName();
            $command = \Yii::$app->db->createCommand(
                "UPDATE {$table} SET"
                ." `right`=`right`+:rightDelta"
                ." WHERE `left`<=:left AND `subtree`=:subtree"
            );
            $command->prepare();
        }

        return $command;
    }

    private function createChildPlace(Category $parent): void
    {
        $this->getChildrenUpdateCommand()
            ->bindValues([
                ':left'    => $parent->left,
                ':subtree' => $parent->subtree,
            ])
            ->execute();
        $this->getParentsUpdateCommand()
            ->bindValues([
                ':rightDelta' => 2,
                ':left'       => $parent->left,
                ':subtree'    => $parent->subtree,
            ])
            ->execute();
    }

}
