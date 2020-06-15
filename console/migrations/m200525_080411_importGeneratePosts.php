<?php

use vova07\prisons\models\PostIso;
use vova07\prisons\models\PostDict;
use vova07\prisons\models\Post;
use vova07\base\components\CsvFile;
use vova07\prisons\models\Division;
/**
 * Class m200525_080431_officerPostId
 */
class m200525_080411_importGeneratePosts extends \vova07\base\components\MigrationWithModelGenerator
{
    const MATERIALS_DIR = '@common/../_materials/';
    const FILENAME_POST_DICTS = "prisons_officer_post_dict.tsv";

    public $models = [
        PostIso::class,
        PostDict::class,
        Post::class
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->generateModelTables();
        $this->importPostIso();
      //  $this->login();
       // $this->generatePosts();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        \Yii::$app->db->createCommand('DELETE FROM ' . PostDict::tableName())->execute();
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
        $this->dropModelTables();
        return true;
    }



    private function importPostIso()
    {
        $dataDir =  Yii::getAlias(self::MATERIALS_DIR);
        $csvFile = $dataDir . self::FILENAME_POST_DICTS;
        $fileHandler = fopen($csvFile,'r');
        $csv = new CsvFile(['fileName' =>$csvFile,'cellDelimiter'=>"\t"]);

        while($csv->eof === false){
            $csv->read();
            $model = new PostIso();
            $model->id = $csv->getField('id');
            $model->code = $csv->getField('code');
            $model->title = $csv->getField('title');
            $model->salary_class_id = $csv->getField('salary_class_id');
            if (!$model->save()){
                print_r($model->getErrors());
            };
     }

    }

    private function generatePosts()
    {
            foreach (Division::find()->all() as $division){
                foreach (PostDict::find()->all() as $postDict){
                    $post = new Post;
                    $post->company_id = $division->company_id;
                    $post->division_id = $division->division_id;
                    $post->postdict_id = $postDict->primaryKey;
                    $post->title = $postDict->title;
                    if (!$post->save()){
                        print_r($post->getErrors());
                    }
                }
            }


    }




}
