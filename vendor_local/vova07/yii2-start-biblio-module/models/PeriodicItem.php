<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\biblio\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Company;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostDict;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class PeriodicItem extends  Ownableitem
{


    public static function tableName()
    {
        return 'biblio_periodic_items';
    }
    public function rules()
    {
        return [

        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                //Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'lib_item_id' => $migration->smallInteger()->notNull(),
                'id' => $migration->primaryKey()->comment('id code of book '),
                'index' => $migration->smallInteger()->notNull(),
                'sub_index' => $migration->tinyInteger()->notNull(),
                'title' => $migration->string()->notNull(),



            ],
            'indexes' => [
                [self::class, ['officer_id', 'company_id', 'division_id', 'postdict_id','rank_id','year','month_no'], true],
            ],

            'foreignKeys' => [


            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [

                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',


                    ],
                ]

            ];
        } else {
            $behaviors = [];
        }

        return $behaviors;
    }

    public static function find()
    {
        return new SalaryQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }





    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'officer_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ownableitem_id'=>'officer_id']);
    }

    public function getOfficerPost()
    {
        return $this->hasOne(OfficerPost::class,[
            'officer_id'=>'officer_id',
            'company_id'=>'company_id',
            'division_id'=>'division_id',
            'postdict_id'=>'postdict_id'

        ]);
    }
    public function getPostDict()
    {
        return $this->hasOne(PostDict::class,[
            'id'=>'postdict_id'

        ]);
    }


    public function getBaseAmount()
    {

        return self::SALARY_MIN_AMOUNT * ($this->postDict->salaryClass->rate + $this->officerPost->benefit_class )  * $this->officerPost->getTimeRate()  ;
    }

    public function calculateAmountRate()
    {
        $monthDaysNumber = Calendar::getMonthDaysNumber((new \DateTime())->setDate($this->year, $this->month_no, 1));
        return $this->getBaseAmount() / $monthDaysNumber * $this->work_days;
    }

    public function calculateAmountCondition()
    {
        return $this->amount_rate * self::CHARGE_SPECIFIC_CONDITIONS_PERCENT / 100;
    }
    public function calculateAmountAdvance()
    {
        return $this->amount_rate * self::CHARGE_ADVANCE_PERCENT / 100;
    }




}
