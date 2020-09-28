<?php
namespace vova07\salary\models\backend;

use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryWithHold;
use vova07\salary\models\SalaryWithHoldQuery;
use vova07\users\models\OfficerView;
use vova07\users\models\PersonView;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class WithholdSearch extends SalaryWithHold
{

    public $atFormat = 'Y-m-01';
    public function rules()
    {
        return [
            [['at'],'safe'],
            [['at'], 'default', 'value' => (new \DateTime())->format($this->atFormat)],
            [['personView.fio'], 'string']
        ];
    }

    public function attributes()
    {
// делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['personView.fio']);
    }

    public function search($params)
    {


        $query = parent::find();

        $query->joinWith(
            [
                'officerView' => function($query) { $query->from([OfficerView::tableName()]); },
                'personView' => function($query) { $query->from([PersonView::tableName()]); }

                //'person' => function($query) { $query->from([Person::tableName()]); }
            ]
        )->orderBy('vw_officer.category_rank_id, vw_person.fio');

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);



        if (!$this->load($params)){
          //  $this->at = (new \DateTime())->format($this->atFormat);
        };
        $this->validate();
        $query->andFilterWhere([
            'year' => $this->year,
            'month_no' => $this->month_no
        ]);
        $query->andFilterWhere(['like',
            'vw_person.fio',
            //'chiu',
            $this->getAttribute('personView.fio')

        ]);




        return $dataProvider;

    }


    public function getAt($format = true)
    {
        if ($this->year && $this->month_no) {
            if ($format)
                return (new \DateTime())->setDate($this->year, $this->month_no, 1)->format($this->atFormat);
            else
                return (new \DateTime())->setDate($this->year, $this->month_no, 1);
        } else {
            if ($format)
                return (new \DateTime())->format($this->atFormat);
            else
                return (new \DateTime());
        }
    }


    public function setAt($value)
    {
        if ($value)
        {
            $dateTime = \DateTime::createFromFormat('Y-m-d',$value);
            $this->year = $dateTime->format('Y');
            $this->month_no = $dateTime->format('m');
        }
    }


}