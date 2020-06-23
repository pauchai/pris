<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\tasks\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\models\PrisonerSecurity;
use vova07\tasks\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use vova07\tasks\models\CommitteeQuery;
use yii\validators\DateValidator;


class Committee extends  Ownableitem
{

    const MARK_NOT_SATISFACTORY = 0;
    const MARK_SATISFACTORY = 1;
    const MARK_GOOD = 2;
    const MARK_REALIZED = 10;
    const MARK_NOT_REALIZED = 11;

    const SUBJECT_91 = 1;
    const SUBJECT_92 = 2;
    const SUBJECT_248_2 = 3;
    const SUBJECT_251 = 4;
    const SUBJECT_AMNISTIA = 5;
    const SUBJECT_POMILOVANIE = 6;


    const STATUS_INIT = 1;
    const STATUS_MATERIALS_ARE_READY = 5;
    const STATUS_FINISHED = 10;

    public static function tableName()
    {
        return 'committee';
    }

    public function rules()
    {
        return [
            [['subject_id', 'prisoner_id', 'assigned_to','status_id'], 'required'],

            [['mark_id'],'integer'],
            [['dateStartJui', 'dateFinishJui'],'date'],
            ['dateFinishJui', 'required', 'when' => function($model){ return $model->status_id == self::STATUS_FINISHED;}
                , 'whenClient' => "function (attribute, value) {
                    return $('#committee-datefinishjui').val() == '" . self::STATUS_FINISHED. "';
                }"]


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
                'subject_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'mark_id' => Schema::TYPE_STRING,
                'assigned_to' => Schema::TYPE_STRING . ' NOT NULL',
                'date_start' => $migration->bigInteger(),
                'date_finish' => $migration->bigInteger(),
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL'
            ],
            'indexes' => [
                [self::class, 'prisoner_id'],
                [self::class, 'mark_id'],
                [self::class, 'assigned_to'],
                [self::class, 'status_id'],
                [self::class, 'date_start']
            ],
            'foreignKeys' => [
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],

            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_UPDATE, function($event) {
            /**
             * @var $event Event
             * @var $committee Committee
             *
             */
            $committee = $event->sender;
            if ($committee->status_id == Committee::STATUS_FINISHED) {
                if ($prisonerSecurity = PrisonerSecurity::findOne([
                    'prisoner_id' => $committee->prisoner_id,
                    'type_id' => [PrisonerSecurity::TYPE_251, PrisonerSecurity::TYPE_250]
                ]))
                    $prisonerSecurity->delete();
            }
        });
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


            ];
        } else {
            $behaviors = [

            ];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_start',
                'juiAttribute' => 'dateStartJui',


            ],
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_finish',
                'juiAttribute' => 'dateFinishJui',


            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new CommitteeQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
    }

    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_INIT => Module::t('default', 'STATUS_INIT'),
            self::STATUS_MATERIALS_ARE_READY => Module::t('default', 'STATUS_MATERIALS_ARE_READY'),
            self::STATUS_FINISHED => Module::t('default', 'STATUS_FINISHED'),
        ];
    }

    public function getStatus()
    {
        return self::getStatusesForCombo()[$this->status_id];
    }

    public static function getSubjectsForCombo()
    {
        return [
            self::SUBJECT_91 => Module::t('default', 'SUBJECT_91'),
            self::SUBJECT_92 => Module::t('default', 'SUBJECT_92'),
            self::SUBJECT_248_2 => Module::t('default', 'SUBJECT_248_2'),
            self::SUBJECT_251 => Module::t('default', 'SUBJECT_251'),
            self::SUBJECT_AMNISTIA => Module::t('default', 'SUBJECT_AMNISTIA'),
            self::SUBJECT_POMILOVANIE => Module::t('default', 'SUBJECT_POMILOVANIE'),

        ];
    }

    public function getSubject()
    {
        return self::getSubjectsForCombo()[$this->subject_id];
    }

    public static function getMarksForCombo()
    {
        return [
            self::MARK_NOT_SATISFACTORY => Module::t('default','MARK_NOT_SATISFACTORY'),
            self::MARK_SATISFACTORY => Module::t('default','MARK_SATISFACTORY'),
            self::MARK_GOOD => Module::t('default','MARK_GOOD'),
            self::MARK_REALIZED => Module::t('default','MARK_REALIZED'),
            self::MARK_NOT_REALIZED => Module::t('default','MARK_NOT_REALIZED'),
        ];
    }
    public function getMark()
    {
        if (is_null($this->mark_id) || $this->mark_id=="")
            return null;
        else
            return self::getMarksForCombo()[$this->mark_id];
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'prisoner_id']);
    }
    public function getAssignedTo()
    {
        return $this->hasOne(Officer::class,['__person_id' => 'assigned_to']);
    }

    public function attributeLabels()
    {
        return [
          'subject_id' => Module::t('labels','SUBJECT_LABEL'),
            'prisoner_id' => Module::t('labels','PRISONER_LABEL'),
            'assigned_to' => Module::t('labels','ASSIGNED_TO_LABEL'),
            'dateStartJui' => Module::t('labels','DATE_START_LABEL'),
            'dateFinishJui' => Module::t('labels','DATE_FINISH_LABEL'),
            'mark_id' => Module::t('labels','MARK_LABEL'),
            'status_id' => Module::t('labels','STATUS_LABEL'),



        ];
    }


}

