<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\models\Ownableitem;

use vova07\documents\models\Document;
use vova07\users\models\Person;

use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Relation extends  Ownableitem
{




    public static function tableName()
    {
        return 'relation';
    }

    public function rules()
    {
        return [
            [['person_id','ref_person_id','type_id'],'required'],
            [['document_id'], 'integer']
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
                'person_id' => Schema::TYPE_INTEGER,
                'ref_person_id' => Schema::TYPE_INTEGER,
                'document_id' => $migration->integer(),
                'type_id' => Schema::TYPE_TINYINT,

            ],
            'primaries' => [
                [self::class, ['person_id','ref_person_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'person_id',Person::class,Person::primaryKey()],
                [get_called_class(), 'ref_person_id',Person::class,Person::primaryKey()],
                [get_called_class(), 'document_id',Document::class,Document::primaryKey()]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'ownableitem',
                ],
            ]
        ];

    }

    public static function find()
    {
        return new RelationQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ownableitem_id'  => 'person_id']);
    }
    public function getRefPerson()
    {
        return $this->hasOne(Person::class,[ '__ownableitem_id'  => 'ref_person_id']);
    }
    public function getType()
    {
        return $this->hasOne(RelationType::class,[ 'id'  => 'type_id']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }




}
