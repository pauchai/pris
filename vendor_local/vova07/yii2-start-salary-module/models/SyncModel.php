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



    public function synchronize()
    {

        foreach (OfficerPost::findAll([
            'company_id' => \Yii::$app->base->company->primaryKey
        ]) as $officerPost)
        {
            $salary = new Salary;
            $salary->officer_id = $officerPost->officer_id;
            $salary->company_id = $officerPost->company_id;
            $salary->division_id = $officerPost->division_id;
            $salary->postdict_id = $officerPost->postdict_id;

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