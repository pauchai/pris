<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/23/20
 * Time: 10:42 AM
 */

namespace vova07\salary\models;


use vova07\prisons\models\Company;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use yii\base\Model;

class SyncModel extends Model
{

    public $year;
    public $month_no;

    public function rules()
    {
        return [
            [['year', 'month_no'],'required']
        ];
    }

    public function synchronize()
    {

        foreach (OfficerPost::findAll([
            'company_id' => \Yii::$app->base->company->primaryKey
        ]) as $officerPost) {
            $monthDays = \vova07\jobs\helpers\Calendar::getMonthDaysNumber((new \DateTime())->setDate($this->year, $this->month_no, '1'));

            $salary = new Salary([
                'officer_id' => $officerPost->officer_id,
                'company_id' => $officerPost->company_id,
                'division_id' => $officerPost->division_id,
                'postdict_id' => $officerPost->postdict_id,


                'rank_id' => $officerPost->officer->rank_id,
                'year' => $this->year,
                'month_no' => $this->month_no,
                'work_days' => $monthDays,
                'amount_rank_rate' => $officerPost->officer->rank->rate


            ]);
            $salary->validate();
           $salary->reCalculate();
            $salary->save();
        }
    }

    /**
     * @return null|Company
     */
    public function getCompany()
    {
        return Company::findOne($this->company_id);
    }
}