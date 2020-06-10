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

    const FK_OFFICER_POST_KEY = 'fk_officer_post_key';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


       $this->addColumn(Officer::tableName(),  'postdict_id',$this->smallInteger());
        $this->addForeignKey(self::FK_OFFICER_POST_KEY, Officer::tableName(), ['company_id', 'division_id', 'postdict_id'],
            Post::tableName(), ['company_id', 'division_id', 'postdict_id']);

        $this->dropColumn(Officer::tableName(),'post');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropForeignKey(self::FK_OFFICER_POST_KEY, Officer::tableName());
        $this->dropColumn(Officer::tableName(), 'postdict_id');
        $this->addColumn(Officer::tableName(), 'post', $this->string());


        return true;
    }






}
