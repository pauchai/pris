<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Post;
/**
 * Class m200525_080431_officerPostId
 */
class m200525_080431_officerPostId extends Migration
{
    const FK_OFFICER_POST_ID = 'fk_officer_post_id';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Officer::tableName(),  'post_id', $this->tinyInteger(3));
        $this->addForeignKey(self::FK_OFFICER_POST_ID, Officer::tableName(), ['company_id', 'division_id', 'post_id'],
            Post::tableName(), ['company_id', 'division_id', 'post_id']);
        $this->dropColumn(Officer::tableName(),'post');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_OFFICER_POST_ID, Officer::tableName());
        $this->dropColumn(Officer::tableName(), 'post_id');
        $this->addColumn(Officer::tableName(), 'post', $this->string());

        return true;
    }


}
