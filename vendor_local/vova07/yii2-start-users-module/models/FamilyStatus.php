<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



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
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class FamilyStatus extends  OwnableItem
{

    //use SaveRelationsTrait;

    const STATUS_MARRIAGE = 0;
    const STATUS_DIVORCE = 10;


    public static function tableName()
    {
        return 'relations';
    }

    public function rules()
    {
        return [
            [['person_id','status_id','document_id'],'required']
        ];
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Person::class) => Schema::TYPE_PK . ' ',
                'person_id' => Schema::TYPE_INTEGER,
                'document_id' => Schema::TYPE_INTEGER,
                'status_id' => Schema::TYPE_TINYINT,

            ],
            'foreignKeys' => [
                [get_called_class(), 'person_id',Person::class,Person::primaryKey()]
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
        return new FamilyStatusQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ident_id'  => '__person_id']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getDocument()
    {
        return $this->hasOne(Document::class,['__ownableitem' => 'document_id']);
    }

    public static function getListForCombo()
    {
        return [
            self::STATUS_DIVORCE => Module::t('programs', 'STATUS_DIVORCE'),
            self::STATUS_MARRIAGE => Module::t('programs', 'STATUS_MARRIAGE'),
        ];
    }


}
