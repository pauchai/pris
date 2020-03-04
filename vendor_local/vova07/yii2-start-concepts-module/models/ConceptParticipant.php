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
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\concepts\Module;
use vova07\countries\models\Country;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ConceptParticipant extends  Ownableitem
{

    public static function tableName()
    {
        return 'concept_participants';
    }
    public function rules()
    {
        return [
            [['concept_id','prisoner_id'], 'required'],
            [['concept_id','prisoner_id'],'unique','targetAttribute' => ['concept_id','prisoner_id'],'message' => Module::t('default','PARTICIPANT_EXISTS')],
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                'concept_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',

            ],
            'primaries' => [
                [self::class,['concept_id','prisoner_id']]
            ],
            'foreignKeys' => [
                [self::class, 'concept_id',Concept::class,Concept::primaryKey()],
                [self::class, 'prisoner_id',Prisoner::class,Prisoner::primaryKey()],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

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
                ]

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new ConceptParticipantQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'__ownableitem_id','title');
    }
    public function getConcept()
    {
        return $this->hasOne(Concept::class,['__ownableitem_id'=>'concept_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'prisoner_id']);
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'prisoner_id']);
    }



}
