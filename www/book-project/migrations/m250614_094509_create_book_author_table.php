<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m250614_094509_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk_book_author', 'book_author', ['book_id', 'author_id']);
        $this->addForeignKey(
            'fk_book_author_book',
            'book_author',
            'book_id',
            'book',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_book_author_author',
            'book_author',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_book_author_author', 'book_author');
        $this->dropForeignKey('fk_book_author_book', 'book_author');
        $this->dropTable('{{%book_author}}');
    }
}
