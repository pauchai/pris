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
                'salary_id' => $migration->integer()->notNull(),
                'amount_pension' => $migration->double('2,2'),
                'is_pension' => $migration->boolean()->defaultValue(true),
                'amount_income_tax' => $migration->double('2,2'),
                'amount_execution_list' => $migration->double('2,2'),
                'amount_labor_union' => $migration->double('2,2'),
                'amount_sick_list' => $migration->double('2,2'),
                'amount_card' => $migration->double('2,2'),

                'balance_id' => $migration->integer(),

            ],
            'primaries' => [
                [self::class, ['salary_id']]
            ],
            'foreignKeys' => [
                  [get_called_class(), ['salary_id'],Salary::class, Salary::primaryKey()],
                  [get_called_class(), ['balance_id'],Balance::class, Balance::primaryKey()],


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



    public function getSalary()
    {
        return $this->hasOne(Salary::class,['__ownableitem_id'=>'salary_id']);
    }

    public function getTotal()
    {
        return
            $this->amount_pension +
            $this->amount_income_tax +
            $this->amount_execution_list +
            $this->amount_labor_union +
            $this->amount_sick_list +
            $this->amount_card
            ;
    }

    public function calculatePension()
    {
        return $this->salary->total / 100 * self::WIHTHOLD_PENSION;
    }
    public function calculateLaborUnion()
    {
        return $this->salary->total / 100 * self::WITHHOLD_LABOR_UNION;
    }



}
