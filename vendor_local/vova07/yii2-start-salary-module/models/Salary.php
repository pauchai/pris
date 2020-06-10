<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;



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



class Salary extends  Ownableitem
{
    const SALARY_MIN_AMOUNT = 1750.50;

    const CHARGE_SPECIFIC_CONDITIONS_PERCENT  = 20;
    const CHARGE_ADVANCE_PERCENT = 10;
    const WIHTHOLD_PENSION  = 6;
    const WITHHOLD_LABOR_UNION = 1;


    public static function tableName()
    {
        return 'salary';
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
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'officer_id' => $migration->integer()->notNull(),
                'company_id' => $migration->integer()->notNull(),
                'division_id' => $migration->tinyInteger(3)->notNull(),
                'postdict_id' => $migration->smallInteger()->notNull(),
                'rank_id' => $migration->integer()->notNull(),
                'year' => $migration->integer()->notNull(),
                'month_no' => $migration->tinyInteger(2)->notNull(),
                'work_days' => $migration->tinyInteger(3)->notNull(),
                'amount_rate' => $migration->double('2,2'),
                'amount_rank_rate' => $migration->double('2,2'),
                'amount_conditions' => $migration->double('2,2'),
                'amount_advance' => $migration->double('2,2'),
                'amount_optional' => $migration->double('2,2'),
                'amount_diff_sallary' => $migration->double('2,2'),
                'amount_additional' => $migration->double('2,2'),
                'amount_maleficence' => $migration->double('2,2'),
                'amount_vacation' => $migration->double('2,2'),
                'amount_sick_list_ch' => $migration->double('2,2'),
                'amount_bonus' => $migration->double('2,2'),

                'amount_pension' => $migration->double('2,2'),
                'amount_income_tax' => $migration->double('2,2'),
                'amount_execution_list' => $migration->double('2,2'),
                'amount_labor_union' => $migration->double('2,2'),
                'amount_sick_list_w' => $migration->double('2,2'),
                'amount_card' => $migration->double('2,2'),

                'balance_id' => $migration->integer()->notNull(),

            ],
            'indexes' => [
                [self::class, ['officer_id', 'company_id', 'division_id', 'postdict_id','rank_id','year','month_no'], true],
            ],

            'foreignKeys' => [
                  [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()],
                  [get_called_class(), ['balance_id'],Balance::class, Balance::primaryKey()],
                  [get_called_class(), ['company_id'],Company::class, Company::primaryKey()],
                  [get_called_class(), ['company_id', 'division_id'],Division::class, Division::primaryKey()],
                  [get_called_class(), ['postdict_id'],PostDict::class, PostDict::primaryKey()],
                  [get_called_class(), ['company_id', 'division_id', 'postdict_id'],Post::class, Post::primaryKey()],
                  [get_called_class(), ['officer_id', 'company_id', 'division_id', 'postdict_id'],OfficerPost::class, OfficerPost::primaryKey()],
                  [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()],


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
        return $this->hasOne(Person::class,['__ident_id'=>'officer_id']);
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
