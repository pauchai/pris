<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 08.10.2019
 * Time: 13:34
 */

namespace vova07\jobs\helpers;


use vova07\jobs\models\Holiday;
use vova07\jobs\models\WorkDay;
use vova07\prisons\models\Penalty;
use vova07\users\models\Prisoner;

class Calendar
{
    const RANGE_MONTH = 1;
    static function isHoliday($dateTime, $format = 'Y-m-d'){
        if (!($dateTime instanceof \DateTime)){
            $dateTime = \DateTime::createFromFormat($format, $dateTime);
        }
        $date = $dateTime->format($format);
        return (Holiday::findOne($date)) XOR WorkDay::findOne($date);
    }

    static function isWeekEnd($dateTime, $format = 'Y-m-d')
    {
        if (!($dateTime instanceof \DateTime)){
            $dateTime = \DateTime::createFromFormat($format, $dateTime);
        }
           $weekDay = $dateTime->format('N');
        return in_array($weekDay, [6,7]);
    }
    static function getListYearMonth($fromDate=null, $monthsLimit = 5)
    {
        if (is_null($fromDate)) {
            $fromDate = new \DateTime();
            //$fromDate->modify('first day of month');
        } else {
            $fromDate = \DateTime::createFromFormat('Y-m-d',$fromDate);
        }
        $items = [];
        $items[$fromDate->format('Y-m-d')]  = $fromDate->format('M, Y');
        for ($i = 0-$monthsLimit; $i <= $monthsLimit; $i++)
        {
            $nDate = clone $fromDate;
            $nDate->modify($i . " month");

            $items[$nDate->format('Y-m-d')] = $nDate->format('M, Y');

        }
        return $items;
    }
    static function getMonthDaysNumber($dateTime)
    {
        return $dateTime->format('t');
    }

    /**
     * @param $date \DateTime|integer
     * @param integer $rangeType
     * @return array
     */
    static function getRangeForDate($date,$rangeType = self::RANGE_MONTH, $format=null)
    {
        /**
         * @var $date \DateTime
         */
        if (is_integer($date)){
            $date = (new \DateTime())->setTimestamp($date);
        } else if(!($date instanceof \DateTime)){
            throw new \LogicException('date variable is not integer or DateTime type');
        }
        $year = $date->format('Y');
        $month = $date->format('m');
        switch ($rangeType){
            case self::RANGE_MONTH:
                $rangeFrom = (new \DateTime())->setDate($year,$month,1)->setTime(0,0,0);
                $rangeTo = (new \DateTime())->setDate($year,$month,$date->format('t'))->setTime(23,59,59);
                if ($format){
                    $range[] = $rangeFrom->format($format);
                    $range[] = $rangeTo->format($format);
                } else {
                    $range[] = $rangeFrom;
                    $range[] = $rangeTo;
                }
                break;
            default:
                throw new \LogicException('rangeType doesnt supported');
        }

        return $range;
    }

    public static function getDateTime($year, $monthNo , $dayNo = 1)
    {
        if ($year && $monthNo)
        {
            return (new \DateTime())->setDate($year,$monthNo,$dayNo);
        } else {
            return  (new \DateTime());
        }


    }

    public static function checkDateInPenaltyForPrisoner(Prisoner $prisoner, \DateTime $date)
    {
        $query = $prisoner->getPenalties()
            ->andWhere(
            ['<=', 'date_start', $date->getTimestamp()]
        )
            ->andWhere(
            ['>=', 'date_finish', $date->getTimestamp() - 3600 * 24]
        );
        return $query->count()>0;
    }
}