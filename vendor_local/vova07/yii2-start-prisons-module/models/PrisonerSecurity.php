<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;


class PrisonerSecurity extends  Ownableitem
{
    const EXPIRATION_ABOUT_DAYS = 30;
    const TYPE_251 = 1;
    const TYPE_246_g = 2;
    const TYPE_250 = 3;

    public static function tableName()
    {
        return 'prisoner_security';
    }

    public function rules()
    {
        return [
            [['prisoner_id',  'type_id'], 'required'],

            [['dateStartJui', 'dateEndJui'], 'date']
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
               // Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prisoner_id' => Schema::TYPE_PK ,
                'date_start' => $migration->bigInteger()->notNull(),
                'date_end' => $migration->bigInteger()->notNull(),
                'type_id' => $migration->tinyInteger()->notNull(),
            ],
            'indexes' => [
                [self::class, 'prisoner_id'],
                [self::class, 'date_start'],
                [self::class, 'date_end'],
                [self::class, 'type_id']
            ],
            'foreignKeys' => [
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],

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
            $behaviors = [

            ];
        }

        $behaviors = ArrayHelper::merge($behaviors,[
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_start',
                'juiAttribute' => 'dateStartJui',

            ],
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_end',
                'juiAttribute' => 'dateEndJui',

            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new PrisonerSecurityQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
    }

    public static function getTypesForCombo()
    {
        return [
            self::TYPE_251 => Module::t('default', 'TYPE_251'),
            self::TYPE_246_g => Module::t('default', 'TYPE_246_g'),
            self::TYPE_250 => Module::t('default', 'TYPE_250'),

        ];
    }

    public function getType()
    {
        return self::getTypesForCombo()[$this->type_id];
    }


    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id'=>'prisoner_id']);
    }



    public function isExpired()
    {
        if ($this->date_end){
            return time()>$this->date_end;
        } else {
            return false;
        }
    }
    public function isAboutExpiration()
    {
        if ($this->date_end && $this->date_end> time())
        {
            $seconds =  $this->date_end - time();
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($this->date_end);

            $dateDiff = $dateTime->diff(new \DateTime());
            return $dateDiff->days < self::EXPIRATION_ABOUT_DAYS;

        }
    }

    public function attributeLabels()
    {
        return [
            'prisoner_id' => Module::t('labels','PRISONER_FIO_LABEL'),
            'type_id' => Module::t('labels','SECURITY_TYPE_LABEL'),
            'dateStartJui' => Module::t('labels','DATE_START_LABEL'),
            'dateEndJui' => Module::t('labels','DATE_END_LABEL'),
        ];
    }

    // overrids Item::delete
    public function delete()
    {
       return ActiveRecord::delete();
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id' => 'prisoner_id']);
    }

}
