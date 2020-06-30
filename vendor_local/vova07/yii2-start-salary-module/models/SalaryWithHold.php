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



class SalaryWithHold extends  Ownableitem
{
    const SALARY_MIN_AMOUNT = 1750.50;

    const CHARGE_SPECIFIC_CONDITIONS_PERCENT  = 20;
    const CHARGE_ADVANCE_PERCENT = 10;
    const WIHTHOLD_PENSION  = 6;
    const WITHHOLD_LABOR_UNION = 1;


    public static function tableName()
    {
        return 'salary_withhold';
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
                'salary_id' => $migration->integer()->notNull(),
                'amount_pension' => $migration->double('2,2'),
                'is_pension' => $migration->boolean()->defaultValue(true),
                'amount_income_tax' => $migration->double('2,2'),
                'amount_execution_list' => $migration->double('2,2'),
                'amount_labor_union' => $migration->double('2,2'),
                'amount_sick_list' => $migration->double('2,2'),
                'amount_card' => $migration->double('2,2'),

                'balance_id' => $migration->integer()->notNull(),

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
        return new SalaryWithHoldQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }





    public function getSalary()
    {
        return $this->hasOne(Salary::class,['__ownableitem_id'=>'salary_id']);
    }





}
