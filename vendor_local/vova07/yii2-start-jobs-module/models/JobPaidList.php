<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\jobs\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\humanitarians\Module;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Prison;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

class JobPaidList extends  Ownableitem
{
    const STATUS_ID_ACTIVE = 1;


    public static function tableName()
    {
        return 'jobs_paid_list';
    }
    public function rules()
    {
        return [
            [[ 'prison_id', 'type_id','half_time','status_id'], 'required'],
            [['assigned_to'],'safe'],
            [['assignedAtJui','deletedAtJui'],'date'],
            ['status_id','default','value'=> self::STATUS_ID_ACTIVE],
            [['comment'], 'string']


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
                'prison_id' => $migration->integer()->notNull(),
                'type_id' => $migration->integer()->notNull(),
                'half_time' => $migration->tinyInteger()->notNull(),
                'assigned_to' => $migration->integer(),
                'assigned_at' => $migration->bigInteger(),
                'status_id' => $migration->tinyInteger()->notNull(),
                'deleted_at' => $migration->bigInteger(),
                'comment' => $migration->string()


            ],
            'indexes' => [
              //  [self::class, ['assigned_to','prison_id','type_id','half_time'],'unique','assigned_prison_type_half'],
                [self::class, 'status_id'],

            ],
            'foreignKeys' => [
                [get_called_class(), 'prison_id', Prison::class, Prison::primaryKey()],
                [get_called_class(), 'type_id', JobPaidType::class, JobPaidType::primaryKey()],
                [get_called_class(), 'assigned_to', Prisoner::class, Prisoner::primaryKey()],
            ],

        ];

        return $metadata;

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
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            'assignedAtJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'assigned_at',
                'juiAttribute' => 'assignedAtJui',
                'preserveNonEmptyValues' => true
            ],
        ]);
        $behaviors = ArrayHelper::merge($behaviors,[
            'deletedAtJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'deleted_at',
                'juiAttribute' => 'deletedAtJui',
                'preserveNonEmptyValues' => true
            ],
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new JobPaidListQuery(get_called_class());

    }

    public function getType()
    {
        return $this->hasOne(JobPaidType::class,['id'=>'type_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ownableitem_id' => 'assigned_to']);
    }
    public function getAssignedTo()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'assigned_to']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function attributeLabels()
    {
        return [
            'assignedTo.person.fio' => \vova07\jobs\Module::t('label','ASSIGNED_TO_PERSON_FIO'),
            'assigned_at' => \vova07\jobs\Module::t('label','ASSIGNED_AT'),
            'assigned_to' => \vova07\jobs\Module::t('label','ASSIGNED_TO'),
            'half_time' => \vova07\jobs\Module::t('label','HALF_TIME'),
            'deleted_at' => \vova07\jobs\Module::t('label','DELETED_AT'),
            'comment' => \vova07\jobs\Module::t('label','COMMENT'),
            'status_id' => \vova07\jobs\Module::t('label','STATUS'),
            'prison_id' => \vova07\jobs\Module::t('label','PRISON_ID'),
            'assignedAtJui' => \vova07\jobs\Module::t('label','ASSIGNED_AT_JUI'),
            'type_id' => \vova07\jobs\Module::t('label','TYPE'),
            'deletedAtJui' => \vova07\jobs\Module::t('label','DELETED_AT_JUI'),

        ];
    }

    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_ID_ACTIVE => \vova07\jobs\Module::t('default', 'STATUS_ACTIVE_LABEL'),
            self::STATUS_ID_DELETED => \vova07\jobs\Module::t('default', 'STATUS_DELETED_LABEL')
        ];
    }
    public function getStatus()
    {
        if ($this->status_id)
            return self::getStatusesForCombo()[$this->status_id];
        else
            return null;
    }



}