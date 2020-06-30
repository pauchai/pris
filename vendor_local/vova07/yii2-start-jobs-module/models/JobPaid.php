<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\jobs\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\finances\models\Balance;
use vova07\jobs\Module;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Prison;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\RequiredValidator;

class JobPaid extends  JobAbstract
{
    const STATUS_INIT = 1;
    const STATUS_READY_PROCESSING = 2;
    const STATUS_PROCESSED = 3;

    public $ignoreHolidayWeekDays = [6];

    public static function tableName()
    {
        return 'jobs_paid';
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['status_id'], 'default', 'value' => self::STATUS_PROCESSED]
        ]);
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'half_time' => $migration->boolean()->notNull(),
                'status_id' => $migration->tinyInteger()->notNull(),
                'balance_id' => $migration->integer(),


            ],
            'indexes' => [
                [self::class, ['prisoner_id', 'prison_id', 'type_id', 'month_no', 'year', 'half_time'], 'unique', 'prisoner_prison_type_month_year_half'],
                [self::class, 'status_id'],
            ],
            'foreignKeys' => [
                [get_called_class(), 'type_id', JobPaidType::class, JobPaidType::primaryKey()],
                [get_called_class(), 'balance_id', Balance::class, Balance::primaryKey()],

            ],

        ];

        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public static function find()
    {
        return new JobPaidQuery(get_called_class());

    }

    public function getJobItem()
    {
        return $this->hasOne(JobPaidList::class, [
            'assigned_to' => 'prisoner_id',
            'prison_id' => 'prison_id',
            'type_id' => 'type_id',
            'half_time' => 'half_time',

        ])->andWhere(['status_id' => JobPaidList::STATUS_ID_ACTIVE]);
    }

    public function getType()
    {
        return $this->hasOne(JobPaidType::class, ['id' => 'type_id']);
    }

    public function getBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'balance_id']);
    }

    public function autoFillDaysHours()
    {
        $dayFromNo = 1;
        $dayToNo = Calendar::getMonthDaysNumber($this->getDateTime());

        $jobItem = $this->getJobItem()->one();
        if ($jobItem->assigned_at)
            $jobItemFromDate = (new \DateTime())->setTimestamp($jobItem->assigned_at);
        else
            $jobItemFromDate = (new \DateTime())->setDate($this->year, $this->month_no, $dayFromNo);
        if ($jobItem->deleted_at)
            $jobItemToDate = (new \DateTime())->setTimestamp($jobItem->deleted_at);
        else
            $jobItemToDate = (new \DateTime())->setDate($this->year, $this->month_no, $dayToNo);


        if ($jobItemFromDate->format('m-Y') == $this->month_no . '-' . $this->year)
            $dayFromNo = $jobItemFromDate->format('d');

        if ($jobItemToDate->format('m-Y') == $this->month_no . '-' . $this->year)
            $dayToNo = $jobItemToDate->format('d');

        for ($i = $dayFromNo; $i <= $dayToNo; $i++) {
            $dayColumn = ((integer)$i) . 'd';
            $dateTime = (new \DateTime())->setDate($this->year, $this->month_no, $i);

            if ($dateTime->format('N') == 6) {
                $this->$dayColumn = $this->type->hours_per_sa;
            } else if (Calendar::isHoliday($dateTime) || Calendar::isWeekEnd($dateTime)) {
                continue;
            } else {
                $this->$dayColumn = $this->type->hours_per_day;
            }
            if ($this->half_time)
                $this->$dayColumn = $this->$dayColumn / 2;
        }
    }

    public function attributeLabels()
    {
        return [
            'half_time' => Module::t('labels', 'HALF_TIME_LABEL'),
            'type.category_id' => Module::t('labels', 'CATEGORY'),
            'prison_id' => Module::t('labels', 'PRISON'),
            'month_no' => Module::t('labels', 'MONTH'),
            'year' => Module::t('labels', 'YEAR'),
            'prisoner_id' => Module::t('labels', 'PRISONER'),
            'type_id' => Module::t('labels', 'TYPE'),
        ];
    }

    public function getWorkDaysWithCompensation($exceptPanalty = false)
    {
        return ($this->half_time ? 0.5 : 1) * $this->getDays($exceptPanalty) * $this->type->getCompensationRatio();
    }

    public function getActualPenalty()
    {
        $monthFirstDayDate = (new \DateTime)->setDate($this->year,$this->month_no, 1);
        $monthLastDayDate = (new \DateTime)->setDate($this->year,$this->month_no, Calendar::getMonthDaysNumber($monthFirstDayDate));

       return $this->prisoner->getPenalties()
            ->orWhere(
                new Expression('date_start BETWEEN :from  AND :to',[':from' => $monthFirstDayDate->getTimestamp(),':to'=>$monthLastDayDate->getTimestamp()])
        )
           ->orWhere(
               new Expression('date_finish BETWEEN :from  AND :to',[':from' => $monthFirstDayDate->getTimestamp(),':to'=>$monthLastDayDate->getTimestamp()])
           )
           ->orWhere(
               new Expression('date_start<=:from  AND date_finish>=:to',[':from' => $monthFirstDayDate->getTimestamp(),':to'=>$monthLastDayDate->getTimestamp()])
        )->one();
    }



}