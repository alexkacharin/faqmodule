<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%faq_article}}`.
 */
class m220504_080645_add_status_column_to_faq_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%faq_article}}', 'status', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%faq_article}}', 'status');
    }
}
