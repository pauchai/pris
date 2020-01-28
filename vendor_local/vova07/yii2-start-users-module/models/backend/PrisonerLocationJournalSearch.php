<?php
namespace vova07\users\models\backend;
use vova07\users\models\PrisonerLocationJournal;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerLocationJournalSearch extends PrisonerLocationJournal
{


    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        $dataProvider->query->orderBy(['at' => 'DESC']);
        return $dataProvider;

    }

}