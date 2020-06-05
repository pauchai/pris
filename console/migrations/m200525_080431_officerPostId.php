<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Post;
use vova07\salary\models\SalaryClass;
use vova07\prisons\models\PostDict;
use vova07\base\ModelGenerator\ModelTableGenerator;
use vova07\base\components\CsvFile;
/**
 * Class m200525_080431_officerPostId
 */
class m200525_080431_officerPostId extends Migration
{

    const FK_OFFICER_COMPANY_DIVISION_POST_ID = 'fk_officer_company_division_post_id';
    const FK_OFFICER_POST_ID = 'fk_officer_post_id';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


       $this->addColumn(Officer::tableName(),  'post_id', $this->integer());
        $this->addForeignKey(self::FK_OFFICER_COMPANY_DIVISION_POST_ID, Officer::tableName(), ['company_id', 'division_id', 'post_id'],
            Post::tableName(), ['company_id', 'division_id', '__ownableitem_id']);
        $this->addForeignKey(self::FK_OFFICER_POST_ID, Officer::tableName(), ['post_id'],
            Post::tableName(), ['__ownableitem_id']);
        $this->dropColumn(Officer::tableName(),'post');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropForeignKey(self::FK_OFFICER_COMPANY_DIVISION_POST_ID, Officer::tableName());
        $this->dropForeignKey(self::FK_OFFICER_POST_ID, Officer::tableName());
        $this->dropColumn(Officer::tableName(), 'post_id');
        $this->addColumn(Officer::tableName(), 'post', $this->string());


        return true;
    }






}
