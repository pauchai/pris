<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\concepts\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\concepts\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;




class Concept extends  Ownableitem
{
    const STATUS_ACTIVE = 1;
    const STATUS_FINISHED = 2;

    public static function tableName()
    {
        return 'concepts';
    }

    public function rules()
    {
        return [
            [['prison_id','assigned_to', 'title', 'slug', 'status_id'], 'required'],

            [['dateStartJui', 'dateFinishJui'], 'date'],
        ];

    }
    public static function getMetadata()
    {

        $migration = new Migration();
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'slug' => Schema::TYPE_STRING . " NOT NULL",
                'date_start' => Schema::TYPE_INTEGER . " NOT NULL",
                'date_finish' => Schema::TYPE_INTEGER . " ",
                'assigned_to' => Schema::TYPE_INTEGER . " NOT NULL",
                'rrule' => $migration->text(),
                'status_id' => Schema::TYPE_TINYINT . " NOT NULL",
            ],
            'indexes' => [
                [self::class, 'prison_id'],
                [self::class, 'date_start'],
                [self::class, 'status_id'],
                [self::class, 'assigned_to'],
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
        return new ConceptQuery(get_called_class());
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
            self::STATUS_ACTIVE => Module::t('default', 'STATUS_ACTIVE'),
            //    self::STATUS_ACTIVE => Module::t('default', 'STATUS_ACTIVE'),
            self::STATUS_FINISHED => Module::t('default', 'STATUS_FINISHED'),
        ];
    }

    public function getStatus()
    {
        return self::getStatusesForCombo()[$this->status_id];
    }


    public function getConceptParticipants()
    {
        return $this->hasMany(ConceptParticipant::class, ['concept_id' => '__ownableitem_id']);
    }

    public function getParticipants()
    {
        return $this->hasMany(Prisoner::class, ['__person_id' => 'prisoner_id'])->via('conceptParticipants');
    }


    public function getClasses()
    {
        return $this->hasMany(ConceptClass::class, ['concept_id' => '__ownableitem_id']);
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
            'category_id' =>  Module::t('labels','CATEGORY_LABEL'),
            'dateStartFromJui' =>  Module::t('labels','DATE_START_FROM_JUI_LABEL'),
            'dateStartToJui' =>  Module::t('labels','DATE_START_TO_JUI_LABEL')
        ];
    }

    public static function getYearsForFilterCombo()
    {
        return ArrayHelper::map(
            self::find()->select(['year' => new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) ")])->orderBy(['year' => SORT_ASC])->distinct()->asArray()->all(),
            'year',
            'year'
        );
    }
}
