<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%faq_article}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%faq_category}}`
 */
class m220217_161055_create_faq_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%faq_article}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%faq_article}}');
    }
}
