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
class m200522_062699_officerPostDictColumnId extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


       $this->addColumn(Officer::tableName(),  'postdict_id',$this->smallInteger());

        $this->dropColumn(Officer::tableName(),'post');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(), 'postdict_id');
        $this->addColumn(Officer::tableName(), 'post', $this->string());


        return true;
    }






}
