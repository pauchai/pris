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
use vova07\salary\helpers\SalaryHelper;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\OfficerView;
use vova07\users\models\Person;
use vova07\users\models\PersonView;
use yii\base\Behavior;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\InlineValidator;


class Salary extends  Ownableitem
{
    const SALARY_MIN_AMOUNT = 1650;

    const CHARGE_SPECIFIC_CONDITIONS_PERCENT  = 20;
    const CHARGE_ADVANCE_PERCENT = 10;
    const WIHTHOLD_PENSION  = 6;
    const WITHHOLD_LABOR_UNION = 1;

    const EDUCATION_SALARY_CLASS_ADDONS = 5;
    const SCENARIO_RECALCULATE = 'recalculate';




    public static function tableName()
    {
        return 'salary';
    }

    public static function attributesForTotal()
    {
        return [
            'amount_rate',
            'amount_rank_rate',
        'amount_conditions',
        'amount_advance',
        'amount_optional',
        'amount_diff_sallary',
        'amount_additional',
        'amount_maleficence',
        'amount_vacation',
        'amount_sick_list',
        'amount_bonus'
        ];
    }
    public function rules()
    {
        return [
            [['company_id', 'officer_id', 'year','month_no'], 'required'],
            [['work_days', 'base_rate', 'rank_rate'], 'number'],
            ['full_time', 'boolean'],
//            ['work_days', function($attribute,$params,$validator)
//            {
//
//                $this->reCalculate();
//
//
//            }, 'on' => self::SCENARIO_RECALCULATE
//            ],


//            [['base_rate'],DefaultValueValidator::class, 'value' => function($model,$attribute){
//                return $model->calculateBaseRate() ;
//            }],
            [[ 'amount_rate', 'amount_rank_rate',
                'amount_conditions', 'amount_advance', 'amount_optional',
                'amount_diff_sallary',
                'amount_additional',
                'amount_maleficence',
                'amount_vacation',
                'amount_sick_list',
                'amount_bonus'
                ], 'number']
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
                'division_id' => $migration->tinyInteger(3),
                'postdict_id' => $migration->smallInteger(),
                'rank_rate' => $migration->decimal(10,2),
                'full_time' => $migration->boolean()->defaultValue(true),
                'base_rate' => $migration->decimal(10,2),

                'year' => $migration->integer()->notNull(),
                'month_no' => $migration->tinyInteger(2)->notNull(),
                'work_days' => $migration->tinyInteger(3),

                'amount_rate' => $migration->decimal(10,2),
                'amount_rank_rate' => $migration->decimal(10,2),
                'amount_conditions' => $migration->decimal(10,2),
                'amount_advance' => $migration->decimal(10,2),
                'amount_optional' => $migration->decimal(10,2),
                'amount_diff_sallary' => $migration->decimal(10,2),
                'amount_additional' => $migration->decimal(10,2),
                'amount_maleficence' => $migration->decimal(10,2),
                'amount_vacation' => $migration->decimal(10,2),
                'amount_sick_list' => $migration->decimal(10,2),
                'amount_bonus' => $migration->decimal(10,2),
                'total' => $migration->decimal(10,2)->notNull(),

                'balance_id' => $migration->integer(),

            ],
            'indexes' => [
                [self::class, ['officer_id', 'company_id', 'division_id', 'postdict_id', 'year','month_no'], true],
            ],


            'foreignKeys' => [
                  [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()],
                  [get_called_class(), ['balance_id'],Balance::class, Balance::primaryKey()],
                  [get_called_class(), ['officer_id', 'balance_id'],Balance::class, ['officer_id', '__ownableitem_id']],

                [get_called_class(), ['company_id'],Company::class, Company::primaryKey()],
                  [get_called_class(), ['year', 'month_no'],SalaryIssue::class, SalaryIssue::primaryKey()],
                 // [get_called_class(), ['company_id', 'division_id'],Division::class, Division::primaryKey()],
                //  [get_called_class(), ['postdict_id'],PostDict::class, PostDict::primaryKey()],
                //  [get_called_class(), ['company_id', 'division_id', 'postdict_id'],Post::class, Post::primaryKey()],
                //  [get_called_class(), ['officer_id', 'company_id', 'division_id', 'postdict_id'],OfficerPost::class, OfficerPost::primaryKey()],



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
                ],
                'beforeSave' =>  [
                    'class' => AttributeBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => 'total',
                        ActiveRecord::EVENT_BEFORE_UPDATE => 'total',
                    ],
                    'value' => function ($event) {
                        /**
                         * @var $event Event
                         */
                        $event->sender->reCalculate();
                        return $event->sender->calculateTotal();
                    },
                ],



            ];
        } else {
            $behaviors = [];
        }

        return $behaviors;
    }

    public static function find()
    {
        return new SalaryQuery(Salary::class);
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }





    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'officer_id']);
    }
    public function getOfficerView()
    {
        return $this->hasOne(OfficerView::class,['__person_id'=>'officer_id']);
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'officer_id']);
    }
    public function getPersonView()
    {
        return $this->hasOne(PersonView::class,['__ident_id'=>'officer_id']);
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
    public function getIssue()
    {
        return $this->hasOne(SalaryIssue::class,['year' => 'year', 'month_no' => 'month_no']);
    }
    public function getPost()
    {
        return $this->hasOne(Post::class,[
            'company_id'=>'company_id',
            'division_id'=>'division_id',
            'postdict_id'=>'postdict_id'

        ]);
    }

    public function calculateBaseRate()
    {
        return SalaryHelper::calculateBaseRate($this->postDict->postIso->salaryClass->primaryKey, $this->officerPost->benefit_class, $this->officer->has_education );

    }

    public function calculateAmountRate()
    {
        $monthDaysNumber = Calendar::getMonthDaysNumber((new \DateTime())->setDate($this->year, $this->month_no, 1));
        return SalaryHelper::calculateAmountRate($this->base_rate,  $this->work_days / $monthDaysNumber, $this->getTimeRate() );

    }


    public function calculateAmountRankRate()
    {
        $monthDaysNumber = Calendar::getMonthDaysNumber((new \DateTime())->setDate($this->year, $this->month_no, 1));
        return SalaryHelper::calculateAmountRankRate($this->rank_rate, $this->work_days / $monthDaysNumber, $this->getTimeRate());
    }

    public function calculateAmountCondition()
    {
        return $this->amount_rate * self::CHARGE_SPECIFIC_CONDITIONS_PERCENT / 100;
    }
    public function calculateAmountAdvance()
    {
        return $this->amount_rate *  self::CHARGE_ADVANCE_PERCENT / 100;
    }


    public function reCalculate()
    {
        if (is_null($this->work_days)){
            $firstDayDate = (new \DateTime())->setDate($this->year, $this->month_no, 1);
           $this->work_days = Calendar::getMonthDaysNumber($firstDayDate);
        }

        if (is_null($this->full_time))
            $this->full_time = floatval(ArrayHelper::getValue($this, 'officerPost.full_time'));
        if (is_null($this->rank_rate))
            $this->rank_rate = floatval(ArrayHelper::getValue($this, 'officer.rank.rate'));
        if (is_null($this->base_rate))
            $this->base_rate = $this->officerPost->base_rate?$this->officerPost->base_rate:$this->calculateBaseRate();

        if (is_null($this->amount_rate))
            $this->amount_rate = $this->calculateAmountRate();

        if (is_null($this->amount_rank_rate))
            $this->amount_rank_rate = $this->calculateAmountRankRate();

        if (is_null( $this->amount_conditions))
            $this->amount_conditions = $this->calculateAmountCondition();
        if (is_null( $this->amount_advance))
            $this->amount_advance = $this->calculateAmountAdvance();
    }
    public function calculateTotal()
    {
        $amountTotal = 0;

        foreach (self::attributesForTotal() as $attributeName)
                    $amountTotal += floatval(ArrayHelper::getValue($this, $attributeName, 0));
        return $amountTotal;
    }

    public function getSalaryIssue()
    {
        return $this->hasOne(SalaryIssue::class, ['month_no' => 'month_no', 'year' => 'year']);
    }

    public function getBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'balance_id']);
    }
    public function getWithHold()
    {
        return $this->hasOne(SalaryWithHold::class, ['salary_id' => '__ownableitem_id']);
    }

    public function attributeLabels()
    {
        return [
            'work_days' => Module::t('default', 'WORK_DAYS_LABEL'),
            'base_rate' => Module::t('default', 'BASE_RATE_LABEL'),
            'amount_rate' => Module::t('default', 'AMOUNT_RATE_LABEL'),
            'amount_rank_rate' => Module::t('default', 'AMOUNT_RANK_RATE_LABEL'),
            'amount_conditions' => Module::t('default', 'AMOUNT_CONDITIONS_LABEL {0}% ', self::CHARGE_SPECIFIC_CONDITIONS_PERCENT),
            'amount_advance' => Module::t('default', 'AMOUNT_ADVANCE_LABEL {0}%', self::CHARGE_ADVANCE_PERCENT),
            'amount_optional' => Module::t('default', 'AMOUNT_OPTIONAL_LABEL'),
            'amount_diff_sallary' => Module::t('default', 'AMOUNT_DIFF_SALLARY_LABEL'),
            'amount_additional' => Module::t('default', 'AMOUNT_ADDITIONAL_LABEL'),
            'amount_maleficence' => Module::t('default', 'AMOUNT_MALEFICENCE_LABEL'),
            'amount_vacation' => Module::t('default', 'AMOUNT_VACATION_LABEL'),
            'amount_sick_list' => Module::t('default', 'AMOUNT_SICK_LIST_LABEL'),
            'amount_bonus' => Module::t('default', 'AMOUNT_BONUS_LABEL'),
            'total' => Module::t('default', 'TOTAL_CHARGE_LABEL'),
            'rank_rate' => Module::t('default', 'RANK_RATE_LABEL'),
            'full_time' => Module::t('default', 'FULL_TIME_LABEL'),

        ];
    }

    public function getTimeRate()
    {
        return $this->full_time?1:0.5;
    }




}
