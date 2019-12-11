<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\documents\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\Module;
use vova07\users\models\Person;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
  * Class Document
 * @package vova07\prisons\models
 *
 * @property string $title Title
 */

class Document extends  Ownableitem
{


    const TYPE_PASSPORT = 1;
    const TYPE_ID = 2;
    const TYPE_F9 = 3;
    const TYPE_BIRTH_CERT = 4;
    const TYPE_AVIZ = 6;
    const TYPE_DEMERS = 7;
    const TYPE_APPLICATION_CONDEMNED = 8;
    const TYPE_PROCURA = 8;
    const TYPE_PHOTO = 10;
    const TYPE_AVIZ_PROV = 11;
    const TYPE_ID_PROV = 12;
    const TYPE_AVIZ_F9 = 13;
    const TYPE_MARRIAGE_CERT = 13;
    const TYPE_BILL_OF_DIVORCE = 14;

    const TYPE_DIE_CERT = 15;
    const TYPE_MARRIEGE_ORDER = 16;
    const TYPE_BIRTH_ORDER = 17;
    const TYPE_TRAVEL_DOCUMENT = 18;
    const TYPE_APPATRIDE_DOCUMENT = 19;
    const TYPE_USSR_PASSPORT = 20;



    const STATUS_NOT_ACTIVE = 0;
    const STATUS_IN_PROCESS = 9;
    const STATUS_ACTIVE = 10;

    const EXPIRATION_ABOUT_DAYS = 30;
    public static function tableName()
    {
        return 'document';
    }
    public function rules()
    {
        return [
            [['type_id', 'country_id', 'status_id','person_id'], 'required'],
            [['seria','IDNP'],'string'],
            [['date_issue', 'date_expiration'], 'integer'],
            [['dateIssueJui','dateExpirationJui'],'date'],
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
                'type_id' => Schema::TYPE_TINYINT . ' NOT NULL',
                'country_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'person_id' => Schema::TYPE_INTEGER . ' NOT NULL' ,
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL' ,
                'seria' => Schema::TYPE_STRING . ' NOT NULL' ,
                'IDNP' =>  Schema::TYPE_STRING. ' ',

                'date_issue' => Schema::TYPE_INTEGER . ' NOT NULL',
                'date_expiration' => Schema::TYPE_INTEGER .'(12)' ,

            ],
            'indexes' => [
                [self::class, ['type_id','country_id','person_id'], true],
                [self::class, ['status_id']],
                [self::class, ['date_issue']],
                [self::class, ['date_expiration']],
            ],
            'foreignKeys' => [
                [get_called_class(), 'country_id',Country::class,Country::primaryKey()],
                [get_called_class(), ['person_id'],Person::class, Person::primaryKey()]
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
                        'country',
                        'person',


                    ],
                ],
                'dateIssueJui'=>[
                        'class' => DateJuiBehavior::className(),
                        'attribute' => 'date_issue',
                        'juiAttribute' => 'dateIssueJui'
                ],
                'dateExpirationJui'=>[
                    'class' => DateJuiBehavior::className(),
                    'attribute' => 'date_expiration',
                    'juiAttribute' => 'dateExpirationJui'
                ]

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new DocumentQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }


    /*
    public function beforeValidate()
    {
        return $this->beforeSaveActiveRecordMetaModel(true);
    }*/


    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
        //return $this->hasMany(Company)
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ident_id'=>'person_id']);
    }

    public static function getTypesForCombo()
    {
        return [
            self::TYPE_PASSPORT => Module::t('documents', "TYPE_PASSPORT"),
            self::TYPE_ID => Module::t('documents', "TYPE_ID"),
            self::TYPE_ID_PROV => Module::t('documents', "TYPE_ID_PROV"),
            self::TYPE_F9 => Module::t('documents', "TYPE_F9"),
            self::TYPE_BIRTH_CERT => Module::t('documents', "TYPE_BIRTH_CERT"),
           // self::TYPE_AVIZ => Module::t('documents', "TYPE_AVIZ"),
          //  self::TYPE_DEMERS => Module::t('documents', "TYPE_DEMERS"),
            self::TYPE_APPLICATION_CONDEMNED => Module::t('documents', "TYPE_APPLICATION_CONDEMNED"),
          //  self::TYPE_PROCURA => Module::t('documents', "TYPE_PROCURA"),
           // self::TYPE_PHOTO => Module::t('documents', "TYPE_PHOTO"),
           // self::TYPE_AVIZ_PROV => Module::t('documents', "TYPE_AVIZ_PROV"),
            self::TYPE_MARRIAGE_CERT => Module::t('documents', "TYPE_MARRIAGE_CERT"),
            self::TYPE_BILL_OF_DIVORCE => Module::t('documents', "TYPE_BILL_OF_DIVORCE"),
            self::TYPE_DIE_CERT => Module::t('documents', "TYPE_DIE_CERT"),
            self::TYPE_MARRIEGE_ORDER => Module::t('documents', "TYPE_MARRIEGE_ORDER"),
            self::TYPE_BIRTH_ORDER => Module::t('documents', "TYPE_BIRTH_ORDER"),
            self::TYPE_TRAVEL_DOCUMENT => Module::t('documents', "TYPE_TRAVEL_DOCUMENT"),
            self::TYPE_APPATRIDE_DOCUMENT => Module::t('documents', "TYPE_APPATRIDE_DOCUMENT"),
            self::TYPE_USSR_PASSPORT => Module::t('documents', "TYPE_USSR_PASSPORT"),
        ];



    }
    public  function getType()
    {
        $availableTypes = static::getTypesForCombo();
        return $availableTypes[$this->type_id];
    }

    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_NOT_ACTIVE => Module::t('documents', "STATUS_NOT_ACTIVE"),
            self::STATUS_IN_PROCESS => Module::t('documents', "STATUS_INT_PROCESS"),
            self::STATUS_ACTIVE => Module::t('documents', "STATUS_ACTIVE"),
        ];
    }

    public  function getStatus()
    {
        $availableStatuses = static::getStatusesForCombo();
        return $availableStatuses[$this->status_id];
    }

    public function isExpired()
    {
        if ($this->date_expiration){
            return time()>$this->date_expiration;
        } else {
            return false;
        }
    }
    public function isAboutExpiration()
    {
        if ($this->date_expiration && $this->date_expiration > time())
        {
            $seconds =  $this->date_expiration - time();
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($this->date_expiration);

            $dateDiff = $dateTime->diff(new \DateTime());
            return $dateDiff->days < self::EXPIRATION_ABOUT_DAYS;

        }
    }

    public function attributeLabels()
    {
        return [
            'person_id' => Module::t('labels', "DOCUMENT_PERSON_LABEL"),
            'type_id' => Module::t('labels', "DOCUMENT_TYPE_LABEL"),
            'date_issue' => Module::t('labels', "DOCUMENT_DATE_ISSUE_LABEL"),
            'dateIssueJui' => Module::t('labels', "DOCUMENT_DATE_ISSUE_LABEL"),
            'dateExpirationJui' => Module::t('labels', "DOCUMENT_DATE_EXPIRATION_LABEL"),
            'status_id' => Module::t('labels', "DOCUMENT_STATUS_LABEL"),
            'title' => Module::t('labels', "DOCUMENT_TITLE_LABEL"),
        ];
    }


}