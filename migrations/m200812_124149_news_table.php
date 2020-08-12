<?php

use yii\db\Migration;

/**
 * Class m200812_124149_news_table
 */
class m200812_124149_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (!$this->db->schema->getTableSchema('{{%news}}')) {
            $this->createTable('{{%news}}',
                [
                    'id'        => $this->primaryKey()->comment('ID.'),
                    'title'     => $this->string(255)->comment('Наименование.'),
                    'content'   => $this->text()->comment('Текст статьи.'),
                    'ownerId'   => $this->integer()->notNull()->comment('Создатель.'),
                    'createdAt' => $this->dateTime()->notNull()->comment('Создано.'),
                    'updatedAt' => $this->dateTime()->notNull()->comment('Обновлено.'),
                ]
            );
            $this->createIndex(
                'idx-news-createdAt',
                '{{%news}}',
                'createdAt'
            );
            $this->populateNews();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('{{%news}}')) {
            $this->dropIndex('idx-news-createdAt', '{{%news}}');
            $this->dropTable('{{%news}}');
        }
    }

    private function populateNews()
    {
        $generator = require __DIR__.'/population/NewsGenerator.php';
        foreach ($generator(20) as $item) {
            $this->insert('{{%news}}', $item);
        }
    }

}
