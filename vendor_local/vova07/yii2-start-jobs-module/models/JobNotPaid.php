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
use vova07\humanitarians\Module;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

class JobNotPaid extends  JobAbstract
{
    public $ignoreHolidayWeekDays = true;

    public static function tableName()
    {
        return 'jobs_not_paid';
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'indexes' => [
                [self::class, ['prisoner_id','prison_id','type_id','month_no','year'],'unique','prisoner_prison_type_month']
            ],
            'foreignKeys' => [
                [get_called_class(), 'type_id', JobNotPaidType::class, JobNotPaidType::primaryKey()],

            ],
        ];
        for ($d = 1 ; $d<=31;$d++){
            $fieldName =  strtr(self::dayColFormat,['{day}' => $d]);
            $metadata['fields'][$fieldName] = Schema::TYPE_DOUBLE . '(2,2)';
        }
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());
    }

    public static function find()
    {
        return new JobNotPaidQuery(get_called_class());

    }
    public function getType()
    {
        return $this->hasOne(JobNotPaidType::class,['id'=>'type_id']);
    }











}