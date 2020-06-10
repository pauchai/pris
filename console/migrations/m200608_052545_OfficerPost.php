<?php

use yii\db\Migration;
use vova07\base\components\MigrationWithModelGenerator ;
use vova07\prisons\models\OfficerPost;
use vova07\users\models\Officer;

/**
 * Class m200608_052545_OfficerPost
 */
class m200608_052545_OfficerPost extends MigrationWithModelGenerator
{
    public $models = [
        OfficerPost::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->generateModelTables();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropModelTables();


        return true;
    }

}
