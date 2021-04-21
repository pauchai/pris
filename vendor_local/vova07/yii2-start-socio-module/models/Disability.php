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
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Disability extends  Ownableitem
{




    public static function tableName()
    {
        return 'disability';
    }

    public function rules()
    {
        return [
          [['person_id','group_id'], 'required'],
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
                'group_id' => Schema::TYPE_TINYINT,
                'document_id' => $migration->integer(),

            ],
            'primaries' => [
                [self::class, ['person_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'person_id',Person::class,Person::primaryKey()],
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
        return new DisabilityQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ownableitem_id'  => 'person_id']);
    }
    public function getGroup()
    {
        return DisabilityGroup::build($this->group_id);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }




}
