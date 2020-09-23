<?php

use yii\db\Migration;
use vova07\prisons\models\OfficerPost;

/**
 * Class m200916_110532_dbOfficerPostAddColumnBaseRate
 */
class m200916_110532_dbOfficerPostAddColumnBaseRate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(OfficerPost::tableName(),  'base_rate',OfficerPost::getMetadata()['fields']['base_rate']);



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(OfficerPost::tableName(), 'base_rate');

        return true;
    }
}
