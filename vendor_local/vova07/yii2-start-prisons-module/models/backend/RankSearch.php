<?php
namespace vova07\prisons\models\backend;

use vova07\prisons\models\Cell;
use vova07\prisons\models\Rank;
use vova07\prisons\Module;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class RankSearch extends Rank
{


    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Rank::find()
        ]);
        $this->load($params);
        $this->validate();

        return $dataProvider;

    }

}