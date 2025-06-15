<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_assignment}}`.
 */
class m250614_095252_create_auth_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
        ]);

        $this->insert('{{%auth_assignment}}', [
            'item_name' => 'guest',
            'user_id' => '0',
            'created_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_assignment}}');
    }
}
