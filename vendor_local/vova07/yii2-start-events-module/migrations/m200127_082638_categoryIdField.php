<?php

use yii\db\Migration;

/**
 * Class m200127_082638_categoryIdField
 */
class m200127_082638_categoryIdField extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;


        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }

        $this->addColumn(\vova07\events\models\Event::tableName(),'category_id', $this->tinyInteger());
        $this->createIndex("category_idx", \vova07\events\models\Event::tableName(), "category_id");



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\vova07\events\models\Event::tableName(),'category_id');
        return true ;

    }
}
