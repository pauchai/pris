<?php
namespace vova07\finances\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\jobs\Module;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\db\Query;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class BalanceByPrisonerWithCategoryViewSearch extends BalanceByPrisonerWithCategoryView
{
    public $only_debt = false;
  //  public $sector_id;

    public function init()
    {

    }
    public function rules()
    {
        return [
            [['prisoner_id','only_debt','prisoner.sector_id'],'safe']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->query->orderBy(['fio'=>SORT_ASC]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->getAttribute('prisoner.sector_id')){
            $prisonerQuery = (new Query())->select('__person_id')->where(['sector_id' => $this->getAttribute('prisoner.sector_id')])->from(Prisoner::tableName());
            $query->andWhere(
                ['prisoner_id' => $prisonerQuery]
            );
        }


        $query->andFilterWhere(
            [
                'prisoner_id' => $this->prisoner_id,

            ]
        );
        if($this->only_debt==true){
            $query->andWhere(
               'remain<0'
            );
        }




        return $dataProvider;

    }

    public function attributes()
    {
        return array_merge(parent::attributes(),['prisoner.sector_id']);
    }




}