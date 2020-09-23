<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/16/20
 * Time: 2:17 PM
 */

namespace vova07\salary\helpers;


use vova07\prisons\models\PostDict;
use vova07\prisons\models\PostIso;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryClass;

class SalaryHelper
{
    public static function calculateBaseRate($officerPostSalaryClassId, $officerBenefitClassId, $officerHasEducation = true )
    {

        $resultSalaryClassid = $officerPostSalaryClassId +  $officerBenefitClassId - (!$officerHasEducation?Salary::EDUCATION_SALARY_CLASS_ADDONS:0);
        $salaryClass = SalaryClass::findOne($resultSalaryClassid);
        return self::calculateBaseRateFromSalaryClass($salaryClass->rate);
    }
    public static function calculateBaseRateFromSalaryClass($salaryClassRate)
    {
        return ceil(Salary::SALARY_MIN_AMOUNT * $salaryClassRate /10) * 10;
    }


    public static function calculateAmountRate($baseRate, $workDaysRate, $timeRate)
    {
        return ($baseRate  ) * $timeRate * $workDaysRate ;
    }

    public static function calculateAmountRankRate($rankRate, $workDaysRate, $timeRate)
    {
        return $rankRate * $timeRate * $workDaysRate ;
    }
}

