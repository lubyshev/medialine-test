<?php
declare(strict_types=1);

use app\models\Category;
use app\repositories\CategoriesMigrationRepository;
use yii\db\Migration;

/**
 * Class m200812_213153_categories_table
 */
class m200812_213153_categories_table extends Migration
{
    private CategoriesMigrationRepository $repo;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->db   = 'db';
        $this->repo = new CategoriesMigrationRepository();
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        if (!$this->db->schema->getTableSchema('{{%categories}}')) {
            $this->createTable('{{%categories}}',
                [
                    'id'        => $this->primaryKey()->comment('ID.'),
                    'title'     => $this->string(255)->comment('Наименование.'),
                    'subtree'   => $this->integer()->notNull()->unsigned()->comment('Поддерево.'),
                    'level'     => $this->smallInteger()->notNull()->unsigned()->comment('Глубина.'),
                    'left'      => $this->integer()->notNull()->unsigned()->comment('Левый край.'),
                    'right'     => $this->integer()->notNull()->unsigned()->comment('Правый край.'),
                    'parentId'  => $this->integer()->comment('Родительская категория.'),
                    'ownerId'   => $this->integer()->notNull()->comment('Создатель.'),
                    'createdAt' => $this->dateTime()->notNull()->comment('Создано.'),
                    'updatedAt' => $this->dateTime()->notNull()->comment('Обновлено.'),
                ]
            );
            $this->createIndex(
                'idx-categories-title',
                '{{%categories}}',
                'title'
            );
            $this->createIndex(
                'idx-categories-subtree',
                '{{%categories}}',
                'subtree'
            );
            $this->createIndex(
                'idx-categories-level',
                '{{%categories}}',
                'level'
            );
            $this->createIndex(
                'idx-categories-left',
                '{{%categories}}',
                'left'
            );
            $this->createIndex(
                'idx-categories-right',
                '{{%categories}}',
                'right'
            );
            $this->populateCategories();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%categories}}')) {
            $this->dropIndex('idx-categories-title', '{{%categories}}');
            $this->dropIndex('idx-categories-subtree', '{{%categories}}');
            $this->dropIndex('idx-categories-level', '{{%categories}}');
            $this->dropIndex('idx-categories-left', '{{%categories}}');
            $this->dropIndex('idx-categories-right', '{{%categories}}');
            $this->dropTable('{{%categories}}');
        }
    }

    private function populateCategories()
    {
        $categories = require __DIR__.'/population/categories.php';
        $this->insertCategories($categories);
    }

    private function insertCategories(array $categories, ?Category $parent = null)
    {
        foreach ($categories as $category) {
            $date                  = date('Y-m-d H:i:s', time() - rand(0, 86400 * 180));
            $category['ownerId']   = rand(0, 10) > 5 ? 100 : 101;
            $category['createdAt'] = $date;
            $category['updatedAt'] = $date;
            $model                 =
                $parent
                    ? $this->repo->addChildCategory($parent, $category)
                    : $this->repo->createRootCategory($category);
            $model->save();
            if (!empty($category['items'])) {
                $this->insertCategories($category['items'], $model);
            }
        }
    }
}
