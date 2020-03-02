<?php
namespace vova07\tasks\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class CommitteeNotFinishedSearch extends \vova07\tasks\models\backend\CommitteeSearch
{

    public function search($params)
    {
       $dataProvider = parent::search($params);
        $dataProvider->sort = false;
        $dataProvider->query->notFinished();
        return $dataProvider;

    }
}