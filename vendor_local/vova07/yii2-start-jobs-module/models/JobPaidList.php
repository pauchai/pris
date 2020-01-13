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


    public static function tableName()
    {
        return 'jobs_paid_list';
    }
    public function rules()
    {
        return [
            [[ 'prison_id', 'type_id','half_time'], 'required'],
            [['assigned_to'],'safe'],
            [['assignedAtJui'],'date'],


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
                'assigned_at' => $migration->integer(),


            ],
            'indexes' => [
                [self::class, ['assigned_to','prison_id','type_id','half_time'],'unique','assigned_prison_type_half']
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
        return $this->hasOne(Person::class,['__ident_id' => 'assigned_to']);
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
            'assigned_at' => \vova07\jobs\Module::t('label','ASSIGNED_AT')
        ];
    }

}