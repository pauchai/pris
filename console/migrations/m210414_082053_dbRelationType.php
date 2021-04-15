<?php

use vova07\base\components\MigrationWithModelGenerator;
use vova07\socio\models\RelationType;

/**
 * Class m210414_082053_dbRelationType
 */
class m210414_082053_dbRelationType extends MigrationWithModelGenerator
{
    public $models = [
        RelationType::class,

    ];

    private $records = [
        ['id' => RelationType::ID_PARENTS , 'title' =>'родители'],
        ['id' => RelationType::ID_CHILDREN , 'title' =>'дети'],
        ['id' => RelationType::ID_PARTNER , 'title' =>'супруг'],
        ['id' => RelationType::ID_BROTHER_SISTER , 'title' =>'братья/сестры'],
        ['id' => RelationType::ID_FRIEND , 'title' =>'друзья'],
    ];
    public function safeUp(){
        $this->generateModelTables();
        $this->generateRecords();

    }

    public function generateRecords()
    {
        foreach ($this->records as $record ){
            $model = new RelationType(['id' => $record['id'], 'title' => $record['title'] ]);
            $model->save();
        }

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
