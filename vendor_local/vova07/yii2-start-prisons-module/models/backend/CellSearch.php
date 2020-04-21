<?php
namespace vova07\prisons\models\backend;

use vova07\prisons\models\Cell;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class CellSearch extends Cell
{

    public $sector;
    public $squarePerPrisoner = self::SQUARE_PER_PRISONER_4;

    public function rules()
    {
        return array_merge(
            [
                [['squarePerPrisoner'],'integer']
            ]
        );
    }
    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $this->sector->getCells()
        ]);
        $this->load($params);
        $this->validate();

        return $dataProvider;

    }

}