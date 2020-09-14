<?php

use yii\db\Migration;
use vova07\users\models\Person;

/**
 * Class m200914_093946_dbPersonAddEducationAndOther
 */
class m200914_093946_dbPersonAddEducationAndOther extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Person::tableName(),  'nationality',Person::getMetadata()['fields']['nationality']);
        $this->addColumn(Person::tableName(),  'education',Person::getMetadata()['fields']['education']);
        $this->addColumn(Person::tableName(),  'speciality',Person::getMetadata()['fields']['speciality']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Person::tableName(), 'nationality');
        $this->dropColumn(Person::tableName(), 'education');
        $this->dropColumn(Person::tableName(), 'speciality');

        return true;
    }
}
