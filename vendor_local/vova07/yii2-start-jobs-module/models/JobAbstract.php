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
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

abstract class JobAbstract extends  Ownableitem
{

    const dayColFormat = '{day}d';
    const RATE_POLSTAVKI = 0.5;
    const RATE_STAVKA = 1;
    public $ignoreHolidayWeekDays = [];
    public static function tableName()
    {
        return null;
    }

    public function rules()
    {
        $rules =  [
            [['prison_id','prisoner_id','type_id','type_id','month_no','year'], 'required'],
            [['prison_id','prisoner_id','type_id','type_id','month_no','year'], 'integer'],
        ];
        $dayFieldsArray = [];
        for ($i=1;$i<=31;$i++){
            $dayFieldsArray[] =$i.'d';
        }
        $rules[] = [$dayFieldsArray,'safe'];


        return $rules;
    }


    /**
     *
     */


    public function behaviors()
    {


            $behaviors = [

                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                    ],
                ],

            ];

        return $behaviors;
    }





    public static function getMetadataForMerging()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'month_no' => Schema::TYPE_TINYINT . ' NOT NULL',
                'year' => Schema::TYPE_INTEGER . '(5) NOT NULL',


            ],

            'indexes' => [
                [get_called_class(), 'type_id'],
            ],

            'foreignKeys' => [
                [get_called_class(), 'prison_id', Prison::class, Prison::primaryKey()],
                [get_called_class(), 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],
            ],

        ];
        for ($d = 1 ; $d<=31;$d++){
            $fieldName =  strtr(self::dayColFormat,['{day}' => $d]);
            $metadata['fields'][$fieldName] = Schema::TYPE_DOUBLE . '(2,2)' ;
        }


        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }
    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id'=>'prison_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'prisoner_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id' => 'prisoner_id']);
    }
    public function getDays($exceptPenalty = false)
    {
        $result = 0;
        for ($i = 1; $i<=31;$i++){
            $dateTime = (new \DateTime())->setDate($this->year,$this->month_no, $i);
            if ($exceptPenalty && Calendar::checkDateInPenaltyForPrisoner($this->prisoner,$dateTime))
                continue;

            $colName = strtr(self::dayColFormat,['{day}' => $i]);
            if ($this->$colName){
                $result++;
            }
        }
        return $result;
    }
    public function getHours($exceptPenalty = false)
    {
        $result = 0;
        for ($i = 1; $i<=31;$i++){
            $dateTime = (new \DateTime())->setDate($this->year,$this->month_no, $i);
            if ($exceptPenalty && Calendar::checkDateInPenaltyForPrisoner($this->prisoner,$dateTime))
                continue;
            $colName = strtr(self::dayColFormat,['{day}' => $i]);
            if ($this->$colName){
                $result += $this->$colName;
            }
        }
        return $result;
    }
    public function getDateTime()
    {
        if ($this->year && $this->month_no)
        {
            return (new \DateTime())->setDate($this->year,$this->month_no,1);
        } else {
            return  (new \DateTime());
        }


    }



}