<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription}}`.
 */
class m250614_094609_create_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'guest_phone' => $this->string()->notNull(),
            'created_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_subscription_author',
            'subscription',
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
        $this->dropTable('{{%subscription}}');
    }
}
