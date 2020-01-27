<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\events\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\events\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;


class Event extends  Ownableitem
{

    const STATUS_PLANING = 9;
    const STATUS_ACTIVE = 10;
    const STATUS_FINISHED = 11;

    const CATEGORY_ARTA_PLASTICA = 1;
    const CATEGORY_MASURA_CULTURAL_EDUCATIVE = 2;
    const CATEGORY_SEMINAR_INFORMATIV_BRAINRING = 3;
    const CATEGORY_EXPOZITI_DE_DESENE = 4;
    const CATEGORY_ACTIVITATI_SPORTIVE = 5;

    public static function tableName()
    {
        return 'events';
    }

    public function rules()
    {
        return [
            [['prison_id','assigned_to', 'title', 'slug', 'status_id', 'category_id'], 'required'],

            [['dateStartJui', 'dateFinishJui'], 'date']
        ];

    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'slug' => Schema::TYPE_STRING . " NOT NULL",
                'date_start' => Schema::TYPE_INTEGER . " NOT NULL",
                'date_finish' => Schema::TYPE_INTEGER . " ",
                'assigned_to' => Schema::TYPE_INTEGER . " NOT NULL",
                'status_id' => Schema::TYPE_TINYINT . " NOT NULL",
                'category_id' => Schema::TYPE_TINYINT . " NOT NULL"
            ],
            'indexes' => [
                [self::class, 'prison_id'],
                [self::class, 'date_start'],
                [self::class, 'status_id'],
                [self::class, 'assigned_to'],
                [self::class, 'category_id'],
            ],
            'foreignKeys' => [
                [self::class, 'prison_id', Prison::class, Prison::primaryKey()],
                [self::class, 'assigned_to', Officer::class, Officer::primaryKey()],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'title',
                    'slugAttribute' => 'slug',

                    'ensureUnique' => true,
                ],
                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',


                    ],
                ],
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

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new EventQuery(get_called_class());
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
            self::STATUS_PLANING => Module::t('default', 'STATUS_PLANING'),
        //    self::STATUS_ACTIVE => Module::t('default', 'STATUS_ACTIVE'),
            self::STATUS_FINISHED => Module::t('default', 'STATUS_FINISHED'),
        ];
    }

    public function getStatus()
    {
        return self::getStatusesForCombo()[$this->status_id];
    }


    public function getEventParticipants()
    {
        return $this->hasMany(EventParticipant::class, ['event_id' => '__ownableitem_id']);
    }

    public function getParticipants()
    {
        return $this->hasMany(Prisoner::class, ['__person_id' => 'prisoner_id'])->via('eventParticipants');
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class, ['__company_id'=>'prison_id']);
    }
    public function getAssigned()
    {
        return $this->hasOne(Officer::class, ['__person_id'=>'assigned_to']);
    }

    public  function attributeLabels()
    {
        return [
            'date_start' => Module::t('labels','DATE_START_LABEL'),
            'title' => Module::t('labels','TITLE_LABEL'),
            'assigned.person.fio' => Module::t('labels','ASSIGNED_PERSON_FIO_LABEL'),
            'status_id' => Module::t('labels','STATUS_LABEL'),
        ];
    }

    public static function getCategoriesForCombo()
    {
        return [
            self::CATEGORY_ARTA_PLASTICA => Module::t('default','CATEGORY_ARTA_PLASTICA_TITLE'),
            self::CATEGORY_MASURA_CULTURAL_EDUCATIVE => Module::t('default','CATEGORY_MASURA_CULTURAL_EDUCATIVE_TITLE'),
            self::CATEGORY_SEMINAR_INFORMATIV_BRAINRING => Module::t('default','CATEGORY_SEMINAR_INFORMATIV_BRAINRING_TITLE'),
            self::CATEGORY_EXPOZITI_DE_DESENE => Module::t('default','CATEGORY_EXPOZITI_DE_DESENE_TITLE'),
            self::CATEGORY_ACTIVITATI_SPORTIVE => Module::t('default','CATEGORY_ACTIVITATI_SPORTIVE_TITLE'),
        ];
    }

    public function getCategory()
    {
        if ($this->category_id)
            return self::getCategoriesForCombo()[$this->category_id];
        else
            return null;
    }


}
