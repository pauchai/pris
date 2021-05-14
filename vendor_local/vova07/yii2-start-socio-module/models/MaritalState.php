<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\socio\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class MaritalState extends  OwnableItem
{
    public static function tableName()
    {
        return 'marital_state';
    }

    public function rules()
    {
        return [
            [['__person_id', 'status_id'],'required'],
            [['document_id', 'ref_person_id'], 'integer']
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
                Helper::getRelatedModelIdFieldName(Person::class) => Schema::TYPE_PK . ' ',
                'ref_person_id' => Schema::TYPE_INTEGER,
                'document_id' => $migration->integer(),
                'status_id' => Schema::TYPE_TINYINT,

            ],

            'dependsOn' => [
                Person::class
            ],
            'foreignKeys' => [
              //  [get_called_class(), '__person_id',Person::class,Person::primaryKey()],
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
        return new MaritalStateQuery(get_called_class());
    }


    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ownableitem_id'  => '__person_id']);
    }

    public function getRefPerson()
    {
        return $this->hasOne(Person::class,[ '__ownableitem_id'  => 'ref_person_id']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getDocument()
    {
        return $this->hasOne(Document::class,['__ownableitem_id' => 'document_id']);
    }




    public function getStatus()
    {
        return $this->hasOne(MaritalStatus::class, ['id' => 'status_id']);
    }
    public function attributeLabels()
    {
        return [
            '__person_id' => Module::t('labels', "PERSON_FIO_LABEL"),
            'ref_person_id' => Module::t('labels', "REF_PERSON_LABEL"),
            'status_id' => Module::t('labels', "MARITAL_STATUS_LABEL"),
            'document_id' => Module::t('labels', "DOCUMENT_TITLE_LABEL"),


        ];
    }



}
