<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\concepts\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;

use vova07\base\models\Ownableitem;

use vova07\concepts\Module;

use vova07\plans\models\ConceptVisitQuery;
use vova07\users\models\Prisoner;


use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ConceptClass extends  Ownableitem
{


    public static function tableName()
    {
        return 'concept_classes';
    }

    public function rules()
    {
        return [
            [['concept_id',  'atJui'], 'required'],
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
                'concept_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'at' => $migration->bigInteger()->notNull(),

            ],

            'index' => [

                [get_called_class(), ['at']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'concept_id', Concept::class, Concept::primaryKey()],

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
                ],
                'atJui' => [
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'at',
                    'juiAttribute' => 'atJui'
                ]

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new ConceptClassQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getConcept()
    {
        return $this->hasOne(Concept::class, ['__ownableitem_id' => 'concept_id']);
    }

    public function getVisits()
    {
        return $this->hasMany(ConceptVisit::class, ['class_id' => '__ownableitem_id' ]);
    }



}
