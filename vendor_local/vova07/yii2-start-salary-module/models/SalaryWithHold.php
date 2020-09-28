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
use vova07\users\models\OfficerView;
use vova07\users\models\Person;
use vova07\users\models\PersonView;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;


class SalaryWithHold extends  Ownableitem
{

    const WIHTHOLD_PENSION  = 6;
    const WITHHOLD_LABOR_UNION = 1;


    public static function tableName()
    {
        return 'salary_withhold';
    }
    public function rules()
    {
        return [

            [['amount_pension'],DefaultValueValidator::class, 'value' => function($model,$attribute){
                return $model->calculatePension() ;
            }],
            [['amount_labor_union'],DefaultValueValidator::class, 'value' => function($model,$attribute){
                return $model->calculateLaborUnion() ;
            }],

            [['amount_pension',
                'amount_income_tax',
                'amount_execution_list',
                'amount_labor_union',
                'amount_sick_list',
                'amount_card',
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
                'officer_id' => $migration->integer()->notNull(),
                'year' => $migration->integer()->notNull(),
                'month_no' => $migration->tinyInteger(2)->notNull(),
                'is_pension' => $migration->boolean()->defaultValue(true),
                'member_labor_union' => $migration->boolean()->notNull()->defaultValue(false),

                'amount_pension' => $migration->decimal(10, 2),
                'amount_income_tax' => $migration->decimal(10,2),
                'amount_execution_list' => $migration->decimal(10,2),
                'amount_labor_union' => $migration->decimal(10,2),
                'amount_sick_list' => $migration->decimal(10,2),
                'amount_card' => $migration->decimal(10,2),
                'salary_balance_id' => $migration->integer(),
                'balance_id' => $migration->integer(),

            ],
            'primaries' => [
                [self::class, ['officer_id', 'year','month_no']]
            ],
            'foreignKeys' => [
                [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()],
                [get_called_class(), ['year', 'month_no'],SalaryIssue::class, SalaryIssue::primaryKey()],
                  [get_called_class(), ['balance_id'],Balance::class, Balance::primaryKey()],
                [get_called_class(), ['salary_balance_id'],Balance::class, Balance::primaryKey()],




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
        return new SalaryWithHoldQuery(self::class);
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }



    public function getBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'balance_id']);
    }
    public function getSalaryBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'salary_balance_id']);
    }


    /**
     * @return SalaryQuery
     */
    public function getSalaries()
    {
        return $this->hasMany(Salary::class, ['officer_id' => 'officer_id', 'year' => 'year', 'month_no' => 'month_no']);
    }
    public function getIssue()
    {
        return $this->hasOne(SalaryIssue::class,['year' => 'year', 'month_no' => 'month_no']);
    }
    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id' => 'officer_id']);
    }

    public function getTotal()
    {
        return
            $this->amount_pension +
            $this->amount_income_tax +
            $this->amount_execution_list +
            $this->amount_labor_union -
            $this->amount_sick_list;


    }
    public function getOfficerView()
    {
        return $this->hasOne(OfficerView::class,['__person_id'=>'officer_id']);
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'officer_id']);
    }


    public function reCalculate($doSave = true)
    {
        if (!$this->member_labor_union)
            $this->member_labor_union = $this->officer->member_labor_union;

        $this->amount_pension = $this->calculatePension();
        $this->amount_labor_union = $this->calculateLaborUnion();
        $this->amount_card = $this->calculateAmountCard();
        if ($doSave)
         $this->save();
    }
    public function calculatePension()
    {

        return  (
                $this->getSalaries()->totalAmount() -
                $this->getSalaries()->sum(new Expression('IFNULL(amount_sick_list, 0)'))
            )  / 100 * self::WIHTHOLD_PENSION;
    }
    public function calculateLaborUnion()
    {
        $salaryTotal = $this->getSalaries()->totalAmount();

        return $this->member_labor_union? $salaryTotal / 100 * self::WITHHOLD_LABOR_UNION : 0;
    }
    public function calculateAmountCard()
    {
        return $this->getSalaries()->totalAmount() - $this->getTotal();
    }



    public function attributeLabels()
    {
        return [
            'amount_pension' => Module::t('default', 'AMOUNT_PENSION_LABEL {0}%', self::WIHTHOLD_PENSION),
            'amount_income_tax' => Module::t('default', 'AMOUNT_INCOME_TAX_LABEL'),
            'amount_execution_list' => Module::t('default', 'AMOUNT_EXECUTION_LIST_LABEL'),
            'amount_labor_union' => Module::t('default', 'AMOUNT_LABOR_UNION_LABEL {0}%', self::WITHHOLD_LABOR_UNION),
            'amount_sick_list' => Module::t('default', 'AMOUNT_SICK_LIST_LABEL'),
            'amount_card' => Module::t('default', 'AMOUNT_CARD_LABEL'),
            'total' => Module::t('default', 'TOTAL_WITHHOLD_LABEL'),
            'chargesTotal' => Module::t('default', 'TOTAL_CHARGES_LABEL'),
            'salaryBalance.amount' => Module::t('default', 'SALARY_BALANCE_LABEL'),
            'balance.amount' => Module::t('default', 'BALANCE_LABEL'),
            'officer.balance.remain' => Module::t('default', 'OFFICER_BALANCE_REMAIN_LABEL'),

        ];
    }

    public function getPersonView()
    {
        return $this->hasOne(PersonView::class,['__ident_id'=>'officer_id']);
    }


}
