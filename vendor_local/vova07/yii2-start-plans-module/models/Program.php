<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\controllers\backend\ProgramVisitsController;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\base\Behavior;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Program extends  Ownableitem
{
    //const MARK_NOT_SATISFACTORY = 0;
   // const MARK_SATISFACTORY = 1;
   // const MARK_GOOD = 2;

    const STATUS_ACTIVE =10;
    //const STATUS_FAILED = 11;
    const STATUS_FINISHED =12;



    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_UPDATE, function($event) {
            /**
             * @var $event Event
             * @var $program Program
             *
             */
            $program = $event->sender;
            $dateFinishValue = '';
            if ($program->status_id == Program::STATUS_FINISHED) {
                $programVisits = $program->getProgramVisits()->all();
                foreach ($programVisits as $visit)
                {
                    /**
                     * @var $visit ProgramVisit
                     *
                     */
                    $programPrisoner =$visit->programPrisoner;
                    if (is_null($programPrisoner->mark_id))
                        $programPrisoner->mark_id = $programPrisoner->resolveMark();

                    $programPrisoner->status_id = ProgramPrisoner::STATUS_FINISHED;
                    $programPrisoner->save();
                    $dateFinishValue = $visit->date_visit;
                };

                $program->date_finish = $dateFinishValue;
            }
        });
    }
    public static function tableName()
    {
        return 'programs';
    }
    public function rules()
    {
        return [
            [['programdict_id','prison_id','date_start','order_no','status_id','assigned_to'], 'required'],
          //  [['date_start','date_finish'],'date'],
            [['dateStartJui'],'string'],
            [['order_no'],'string'],


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
                'programdict_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'prison_id' =>  Schema::TYPE_INTEGER . ' NOT NULL ',
                'date_start' => Schema::TYPE_DATE . ' NOT NULL',
                'date_finish' => Schema::TYPE_DATE . ' ',
                'order_no' => Schema::TYPE_STRING .  ' NOT NULL',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL',
                'assigned_to' => $migration->integer()->notNull(),
            ],

            'index' => [
              [self::class,['programdict_id','prison_id','order_no'],true],
                [self::class,['status_id']]
            ],
            'foreignKeys' => [
                [self::class, 'programdict_id',ProgramDict::class,ProgramDict::primaryKey()],
                [self::class, 'prison_id',Prison::class, Prison::primaryKey()],
                [self::class, 'assigned_to',Officer::class, Officer::primaryKey()],


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
                        'prison',



                    ],
                ],

            ];
        } else {
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'date_start',
                'juiAttribute' => 'dateStartJui'

            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new ProgramQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }
    public function getParticipants()
    {
        return $this->hasMany(ProgramPrisoner::class,['program_id'=>'__ownableitem_id']);
    }



    public function getProgramDict()
    {
        return $this->hasOne(ProgramDict::class,['__ownableitem_id' => 'programdict_id']);
    }

    /**
     * @return ProgramVisitQuery
     */

    public function getProgramVisits()
    {
       // return $this->hasMany(ProgramVisit::class,['program_id'=>'__ownableitem_id']);
        $programPrisonerSubQuery =  ProgramPrisoner::find()->select('__ownableitem_id')->where(['program_id'=>$this->primaryKey]);
        return ProgramVisit::find()->where(['program_prisoner_id'=>$programPrisonerSubQuery]);
    }


    public function getAssignedTo()
    {
        return $this->hasOne(Officer::class,['__person_id' => 'assigned_to']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->joinWith('programDict pd')->select([new Expression('programs.__ownableitem_id'),'programdict_id',new Expression("Concat(pd.title, ' ', date_start, ' ' , order_no) as title")])->asArray()->all(),'__ownableitem_id',"title");
    }

    public static function getStatusesForCombo($key = null)
    {
        $ret = [
          self::STATUS_ACTIVE => Module::t('default','STATUS_ACTIVE'),
            self::STATUS_FINISHED => Module::t('default','STATUS_FINISHED'),
        ];

        if ($key)
            return $ret[$key];
        else
            return $ret;

    }

    public function getStatus()
    {
        return static::getStatusesForCombo($this->status_id);
    }

    public function attributeLabels()
    {
        return [
            'programdict_id' => Module::t('labels', 'PROGRAM_TITLE'),
            'prison_id' => Module::t('labels', 'PROGRAM_PRISON'),
            'order_no' => Module::t('labels', 'PROGRAM_ORDER_NO'),
            'dateStartJui' => Module::t('labels', 'PROGRAM_DATE_START'),
            'status_id' => Module::t('labels', 'PROGRAM_STATUS')
        ];

    }
}
