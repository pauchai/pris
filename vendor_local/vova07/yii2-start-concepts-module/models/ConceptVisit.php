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

use vova07\plans\models\ConceptVisitQuery;
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
            [['class_id', 'prisoner_id', 'status_id', 'date_visit'], 'required'],
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
                'class_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'date_visit' => Schema::TYPE_DATE . ' NOT NULL',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL'
            ],

           // 'primaries' => [
            //    [self::class, ['program_id', 'prisoner_id', 'date_visit']]
            //],

            'index' => [

                [get_called_class(), ['status_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'class_id', ConceptClass::class, ConceptClass::primaryKey()],
                [get_called_class(), 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],

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

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id' => 'prisoner_id']);
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
            self::STATUS_DOESNT_PRESENT => 'danger',
            self::STATUS_DOESNT_PRESENT_VALID => 'info',
            self::STATUS_PRESENT => 'success',
        ];
    }
    public function resolveStatusStyle()
    {
        return self::mapStatuseStyle()[$this->status_id];
    }



}
