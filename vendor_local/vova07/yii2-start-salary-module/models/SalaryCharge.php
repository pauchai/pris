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
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class SalaryCharge extends  Ownableitem
{
    const CHARGE_SPECIFIC_CONDITIONS_PERCENT  = 20;
    const CHARGE_ADVANCE_PERCENT = 10;
    const WIHTHOLD_PENSION  = 6;
    const WITHHOLD_LABOR_UNION = 1;


    public static function tableName()
    {
        return 'officer_salary_ch';
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
                'post_id' => $migration->integer()->notNull(),
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
                'amount_sick_list' => $migration->double('2,2'),
                'amount_bonus' => $migration->double('2,2'),
                'balance_id' => $migration->integer()->notNull(),

            ],
            'indexes' => [
                [self::class, ['officer_id', 'post_id','rank_id','year','month_no'], true],
            ],

            'foreignKeys' => [
                  [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()],
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
        return new SalaryChargeQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }





    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'__officer_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'__officer_id']);
    }




}
