<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 * Class ProgramPlan
 * @package vova07\plans\models
 * @deprecated
 */
class ProgramPlan extends  Ownableitem
{
    //const MARK_NOT_SATISFACTORY = 0;
   // const MARK_SATISFACTORY = 1;
   // const MARK_GOOD = 2;

    const STATUS_PLANING =1;
    const STATUS_PLANED =2;
    const STATUS_REALIZED =10;
    const STATUS_REFUSED =12;




    public static function tableName()
    {
        return 'program_plans';
    }
    public function rules()
    {
        return [
            [['prisoner_id','programdict_id'], 'required'],
            [['year'],'integer'],
            [['year'],'safe'],

        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
              //  Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'programdict_id' =>  Schema::TYPE_INTEGER . ' NOT NULL ',
                'year' => Schema::TYPE_INTEGER . '  NULL',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL',
            ],
            'primaries' => [
                [self::class,['prisoner_id','programdict_id']]
            ],



            'index' => [
                [self::class,['status_id']]
            ],
            'foreignKeys' => [
                [self::class, 'programdict_id',ProgramDict::class,ProgramDict::primaryKey()],
                [self::class, 'prisoner_id',Prisoner::class, Prisoner::primaryKey()],

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
                        'programDict',
                        'prisoner',

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
        return new ProgramPlanQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }

    public function getProgramDict()
    {
        return $this->hasOne(ProgramDict::class,['__ownableitem_id' => 'programdict_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->with('programDict')->asArray()->all(),'__ownableitem_id','title');
    }

    public static function getStatusesForCombo()
    {
        return [
          self::STATUS_PLANING => Module::t('default','STATUS_PLANING'),
          self::STATUS_PLANED => Module::t('default','STATUS_PLANED'),
          self::STATUS_REALIZED => Module::t('default','STATUS_REALIZED'),
          self::STATUS_REFUSED => Module::t('default','STATUS_REFUSED'),
        ];
    }
    public static function getYearsForFilterCombo()
    {
        return ArrayHelper::map(self::find()->select('year')->distinct()->asArray()->all(),'year','year');
    }

    public static function getProgramDistinctForCombo()
    {
        return ArrayHelper::map(self::find()->select('programdict_id')->with('programDict')->distinct()->asArray()->all(),'programdict_id','programDict.title');
    }




}
