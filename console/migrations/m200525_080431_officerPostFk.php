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
class m200525_080431_officerPostFk extends Migration
{

    const FK_OFFICER_POST_KEY = 'fk_officer_post_key';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {



        $this->addForeignKey(self::FK_OFFICER_POST_KEY, Officer::tableName(), ['company_id', 'division_id', 'postdict_id'],
            Post::tableName(), ['company_id', 'division_id', 'postdict_id']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropForeignKey(self::FK_OFFICER_POST_KEY, Officer::tableName());


        return true;
    }






}
