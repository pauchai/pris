<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\concepts\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\ModelGenerator\Helper;

use vova07\base\models\Ownableitem;

use vova07\concepts\Module;

use vova07\users\models\Prisoner;


use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ConceptVisit extends  Ownableitem
{
    const STATUS_ABSENT = 1;
    const STATUS_PRESENT = 10;
    const STATUS_ETAP = 11;



    public static function tableName()
    {
        return 'concept_visits';
    }

    public function rules()
    {
        return [
            [['class_id', 'participant_id', 'status_id'], 'required'],
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
               // Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'class_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'participant_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
              //  'at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL'
            ],

            'primaries' => [
                [self::class, ['class_id', 'participant_id']]
            ],

            'index' => [

                [get_called_class(), ['status_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'class_id', ConceptClass::class, ConceptClass::primaryKey()],
                [get_called_class(), 'participant_id', ConceptParticipant::class, ConceptParticipant::primaryKey()],

            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [

                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                        //'program',
                        //'prisoner'


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
        return new ConceptVisitQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getClass()
    {
        return $this->hasOne(ConceptClass::class, ['__ownableitem_id' => 'class_id']);
    }

    public function getParticipant()
    {
        return $this->hasOne(ConceptParticipant::class, ['__ownableitem_id' => 'participant_id']);
    }


    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_ABSENT => Module::t('default', 'CONCEPT_VISIT_ABSENT'),
            self::STATUS_PRESENT => Module::t('default', 'CONCEPT_VISIT_PRESENT'),
            self::STATUS_ETAP => Module::t('default', 'CONCEPT_VISIT_ETAP'),
        ];
    }

    public function getStatus()
    {
        $statuses = self::getStatusesForCombo();
        return $statuses[$this->status_id];
    }

    public static function mapStatuseStyle()
    {
        return [
            self::STATUS_ABSENT => 'danger',
            self::STATUS_PRESENT => 'info',
            self::STATUS_ETAP => 'success',
        ];
    }
    public function resolveStatusStyle()
    {
        return self::mapStatuseStyle()[$this->status_id];
    }



}
