<?php

use vova07\base\components\MigrationWithModelGenerator;
use vova07\prisons\models\Rank;
use vova07\users\models\Officer;
/**
 * Class m200605_062657_ranksTable
 */
class m200605_062657_ranksTable extends MigrationWithModelGenerator
{
    const MATERIALS_DIR = '@common/../_materials/';
    const FILENAME_RANKS = "prisons_ranks.tsv";

    const FK_OFFICER_RANK_ID = "fk_officer_rank_id";

    public $models = [
        \vova07\prisons\models\Rank::class
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->generateModelTables();
        $this->generateRanks();
        $this->addForeignKey(self::FK_OFFICER_RANK_ID, Officer::tableName(), 'rank_id', Rank::tableName(),'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_OFFICER_RANK_ID, Officer::tableName());
        $this->dropModelTables();

        return true;
    }

    private function generateRanks()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . self::FILENAME_RANKS;
        $fileHandler = fopen($csvFile,'r');
        $csv = new \vova07\base\components\CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();
            $model = new Rank();
            $model->id = $csv->getField('id');
            $model->title = $csv->getField('title');
            $model->rate = $csv->getField('rate');
            if (!$model->save()){
                print_r($model->getErrors());
            };
        }

    }
}
