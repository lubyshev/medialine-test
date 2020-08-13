<?php
declare(strict_types=1);

use app\models\Category;
use app\models\News;
use app\repositories\CategoriesMigrationRepository;
use yii\db\Migration;

/**
 * Class m200813_080711_news_categories_table
 */
class m200813_080711_news_categories_table extends Migration
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
    public function safeUp()
    {
        if (!$this->db->schema->getTableSchema('{{%news_categories}}')) {
            $this->createTable('{{%news_categories}}',
                [
                    'id'         => $this->primaryKey()->comment('ID.'),
                    'newsId'     => $this->integer()->notNull()->comment('Новость.'),
                    'categoryId' => $this->integer()->notNull()->comment('Категория.'),
                ]
            );
            $this->createIndex(
                'udx-news_categories-newsId-categoryId',
                '{{%news_categories}}',
                ['newsId', 'categoryId'],
                true
            );
            $this->createIndex(
                'idx-news_categories-categoryId',
                '{{%news_categories}}',
                'categoryId'
            );
            $this->addForeignKey(
                'fk-news_categories-news',
                '{{%news_categories}}',
                'newsId',
                '{{%news}}',
                'id',
                'CASCADE', 'CASCADE'
            );
            $this->addForeignKey(
                'fk-news_categories-categories',
                '{{%news_categories}}',
                'categoryId',
                '{{%categories}}',
                'id',
                'CASCADE', 'CASCADE'
            );
            $this->addCategoriesToNews();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%news_categories}}')) {
            $this->dropForeignKey('fk-news_categories-categories', '{{%news_categories}}',);
            $this->dropForeignKey('fk-news_categories-news', '{{%news_categories}}');
            $this->dropIndex('udx-news_categories-newsId-categoryId', '{{%news_categories}}');
            $this->dropIndex('idx-news_categories-categoryId', '{{%news_categories}}');
            $this->dropTable('{{%news_categories}}');
        }
    }

    private function addCategoriesToNews()
    {
        $categoriesIds = \Yii::$app->db->createCommand(
            'SELECT `id` FROM {{%categories}}'
        )->queryColumn();
        $categoriesMax = count($categoriesIds) - 1;

        foreach (News::find()->all() as $item) {
            $count = rand(1, 3);
            $ids   = [];
            while ($count-- > 0) {
                do {
                    $categoryId = $categoriesIds[rand(0, $categoriesMax)];
                } while (in_array($categoryId, $ids));
                $ids[] = $categoryId;
                $this->insert('{{%news_categories}}', [
                    'newsId'     => $item->id,
                    'categoryId' => $categoryId,
                ]);
            }
        }
    }
}
