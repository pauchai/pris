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
class m200525_080410_importSalaryClasses extends Migration
{
    const MATERIALS_DIR = '@common/../_materials/';
    const FILENAME_SALARY_CLASSES = "prisons_salary_classes.tsv";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->importSalaryClasses();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        \Yii::$app->db->createCommand('DELETE FROM ' . SalaryClass::tableName())->execute();
   \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();


        return true;
    }



    private function importSalaryClasses()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . self::FILENAME_SALARY_CLASSES;
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();
            $salaryClass = new SalaryClass();
            $salaryClass->id = $csv->getField('id');
            $salaryClass->rate = $csv->getField('rate');
            $salaryClass->level = $csv->getField('level');
            if (!$salaryClass->save()){
                print_r($salaryClass->getErrors());
            };
        }

    }





}
