<?php
namespace vova07\humanitarians\models\backend;

use vova07\humanitarians\models\HumanitarianItem;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class HumanitarianByPrisonerPivotSearch extends Model
{
    public $issue_id;
    public $sector_id;
    public $fio;



    public function rules()
    {
        return [
            [['fio', 'sector_id'],'safe']
        ];
    }
    public function search($params)
    {

        $query =  new Query();
        $query->select(['item_id'])->from(['hp' => HumanitarianPrisoner::tableName()]);


        #'29146_item' => new Expression('count(case when item_id=29146  then 1 end)'),
        #'29147_item' => new Expression('count(case when item_id=29147  then 1 end)'),


        #$clone($query)
        $query->where(['issue_id' => $this->issue_id]);
        $query0 = clone($query);

        $fioExpression = new Expression("(CONCAT(p.second_name,' ', p.first_name,' ', p.patronymic))");
        $columns = [
            //'issue_id',
            'prisoner_id',
            'pr.sector_id',
            'fio' => $fioExpression ,
           // 's.title'
        ] ;
        foreach ($query->distinct()->column() as $item_id){
            $columns['item_' . HumanitarianItem::findOne($item_id)->primaryKey] =  new Expression("count(case when item_id=$item_id  then 1 end)");
        }


        $query0->select($columns)
            ->leftJoin(Person::tableName() . ' p','hp.prisoner_id = p.__ownableitem_id')
            ->leftJoin(Prisoner::tableName() . ' pr','hp.prisoner_id = pr.__person_id')
            ->leftJoin(Sector::tableName() . ' s','pr.sector_id = s.__ownableitem_id')
            ->groupBy('issue_id, prisoner_id');;


        $dataProvider = new ActiveDataProvider([
            'query' => $query0
        ]);
        if ($this->load($params)&&$this->validate()){
            $dataProvider->query->andFilterWhere(['sector_id'=>$this->sector_id]);
            $dataProvider->query->andFilterWhere(['like', $fioExpression, $this->fio]);


        }

        return $dataProvider;

    }

    public function getSector()
    {
        return Sector::findOne($this->sector_id);
    }

}