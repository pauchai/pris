<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\psycho\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\psycho\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;

/**
 * @property integer $risk_id
 * @property boolean $feature_violent
 * @property boolean $feature_self_torture
 * @property boolean $feature_sucide
 * @property boolean $feature_addiction_alcohol
 * @property boolean $feature_addiction_drug

 */

class PsyCharacteristic extends  Ownableitem
{

    public const  GROUP_1 = 1;
    public const  GROUP_2 = 2;
    public const  GROUP_3 = 2;




    public const RISK_LOW = 1;
    public const RISK_MEDIUM = 2;
    public const RISK_HIGH = 3;




    public static function tableName()
    {
        return 'psy_characteristics';
    }

    public function rules()
    {
        return [
            ['risk_id', 'integer'],
            [
                [
                  'feature_violent',
                  'feature_self_torture',
                  'feature_sucide',
                  'feature_addiction_alcohol',
                  'feature_addiction_drug',
                ],
                'boolean'],
        ];

    }


    public static function getMetadata()
    {
        $fkFieldName = Helper::getRelatedModelIdFieldName(Person::class);

        $metadata = [
            'fields' => [
//                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                $fkFieldName => Schema::TYPE_PK . ' ',
//                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'risk_id' => Schema::TYPE_TINYINT,
                'feature_violent' => Schema::TYPE_BOOLEAN,
                'feature_self_torture' => Schema::TYPE_BOOLEAN,
                'feature_sucide' => Schema::TYPE_BOOLEAN,
                'feature_addiction_alcohol' => Schema::TYPE_BOOLEAN,
                'feature_addiction_drug' => Schema::TYPE_BOOLEAN,
            ],
            'indexes' => [
                [self::class, 'risk_id'],
                [self::class, 'feature_violent'],
                [self::class, 'feature_self_torture'],
                [self::class, 'feature_sucide'],
                [self::class, 'feature_addiction_alcohol'],
                [self::class, 'feature_addiction_drug'],

            ],

            'dependsOn' => [
                Person::class
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


                    ],
                ],


            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new PsyCharacteristicQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ident_id'=>'__person_id']);
    }



    public static function getRiskForCombo()
    {
        return [
            self::RISK_LOW => Module::t('default','RISK_LOW'),
            self::RISK_MEDIUM => Module::t('default','RISK_MEDIUM'),
            self::RISK_HIGH => Module::t('default','RISK_HIGH'),
        ];
    }

    public function getRisk()
    {
        if ($this->risk_id)
            return self::getRiskForCombo()[$this->risk_id];
        else
            return null;
    }


    public function attributeLabels()
    {


        return [
         //   '__person_id' => Module::t('labels', 'PERSON_ID_LABEL'),

            'risk' => Module::t('labels', 'PSY_RISK_LABEL'),
            'feature_violent' => Module::t('labels', 'PSY_FEATURE_VIOLENT_LABEL'),
            'feature_self_torture' => Module::t('labels', 'PSY_FEATURE_SELF_TORTURE_LABEL'),
            'feature_sucide' => Module::t('labels', 'PSY_FEATURE_SUCIDE_LABEL'),
            'feature_addiction_alcohol' => Module::t('labels', 'PSY_FEATURE_ADDICTION_ALCOHOL_LABEL'),
            'feature_addiction_drug' => Module::t('labels', 'PSY_FEATURE_ADDICTION_DRUG_LABEL'),

        ];

    }



}
