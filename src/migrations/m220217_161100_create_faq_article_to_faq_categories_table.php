<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%faq_article_to_faq_categories}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%faq_article}}`
 * - `{{%faq_category}}`
 */
class m220217_161100_create_faq_article_to_faq_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%faq_article_to_faq_categories}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `article_id`
        $this->createIndex(
            '{{%idx-faq_article_to_faq_categories-article_id}}',
            '{{%faq_article_to_faq_categories}}',
            'article_id'
        );

        // add foreign key for table `{{%faq_article}}`
        $this->addForeignKey(
            '{{%fk-faq_article_to_faq_categories-article_id}}',
            '{{%faq_article_to_faq_categories}}',
            'article_id',
            '{{%faq_article}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-faq_article_to_faq_categories-category_id}}',
            '{{%faq_article_to_faq_categories}}',
            'category_id'
        );

        // add foreign key for table `{{%faq_category}}`
        $this->addForeignKey(
            '{{%fk-faq_article_to_faq_categories-category_id}}',
            '{{%faq_article_to_faq_categories}}',
            'category_id',
            '{{%faq_category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%faq_article}}`
        $this->dropForeignKey(
            '{{%fk-faq_article_to_faq_categories-article_id}}',
            '{{%faq_article_to_faq_categories}}'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            '{{%idx-faq_article_to_faq_categories-article_id}}',
            '{{%faq_article_to_faq_categories}}'
        );

        // drops foreign key for table `{{%faq_category}}`
        $this->dropForeignKey(
            '{{%fk-faq_article_to_faq_categories-category_id}}',
            '{{%faq_article_to_faq_categories}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-faq_article_to_faq_categories-category_id}}',
            '{{%faq_article_to_faq_categories}}'
        );

        $this->dropTable('{{%faq_article_to_faq_categories}}');
    }
}
