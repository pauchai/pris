<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\users\Module;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\FilterValidator;


class Person extends  Ownableitem
{

    public static function tableName()
    {
        return "person";
    }

    public function rules()
    {
        return [
            [['first_name','second_name','patronymic','citizen_id'], 'required'],
            [['birth_year'],'integer'],
            [['address','photo_url','IDNP'],'string'],
            [['photo_url'],'default', 'value'=>'/statics/persons/NoNamePicture.jpg'],
            [['photo_preview_url'],'default', 'value'=>'/statics/persons/NoNamePicturePreview.jpg'],
            [['second_name','first_name','patronymic'],'filter', 'filter'=>function($value){return trim($value);}],



        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Ident::class) => Schema::TYPE_PK . ' ',
                'first_name' => Schema::TYPE_STRING . ' NOT NULL',
                'second_name' => Schema::TYPE_STRING . ' NOT NULL',
                'patronymic' => Schema::TYPE_STRING . ' ',
                'birth_year' => Schema::TYPE_INTEGER . '  UNSIGNED ',
                'photo_url' => Schema::TYPE_STRING . '(64)',
                'photo_preview_url' => Schema::TYPE_STRING . '(64)',
                'citizen_id' => Schema::TYPE_INTEGER . ' ',
                'address' => Schema::TYPE_STRING,
                'IDNP' => Schema::TYPE_STRING. '(15)'
            ],
            'indexes' => [
                [self::class, ['first_name', 'second_name', 'patronymic', 'citizen_id', 'address'], true],
                [self::class, 'birth_year'],
                [self::class, 'citizen_id'],
            ],
            'foreignKeys' => [
                [get_called_class(), 'citizen_id',Country::className(),'id']
            ],
            'dependsOn' => [
                Ident::class
            ]


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
                    'ident',
                    'country'
                ],
            ]
        ];

    }

    public static function find()
    {
        return new PersonQuery(get_called_class());
    }

    public function getIdent()
    {
        return $this->hasOne(Ident::class,['__item_id' => "__ident_id" ]);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }


    public function getCountry()
    {
        return $this->hasOne(Country::class,['id' => 'citizen_id']);
    }

    public function getFio($withoutPatronymic = false)
    {
        if ($withoutPatronymic)
            return $this->second_name . ' ' . $this->first_name;
        else
            return $this->second_name . ' ' . $this->first_name . ' ' . $this->patronymic;
    }


    public static function getListForCombo()
    {
        //return ArrayHelper::map(self::find()->asArray()->all(),'__ident_id','first_name');
        return ArrayHelper::map(self::find()->select(['__ident_id','fio'=>'CONCAT(second_name, " ", first_name," " , patronymic)' ])->asArray()->all(),'__ident_id','fio');
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class,['person_id' => '__ident_id']);
    }
    public function getBuletin()
    {
       return  $this->getDocuments()->orWhere(['type_id' => Document::TYPE_ID])->orWhere(['type_id' => Document::TYPE_PASSPORT])->one();
    }

    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'__ident_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'__ident_id']);
    }
    public function attributeLabels()
    {
        return [
            'IDNP' => Module::t('labels','PERSON_IDNP_LABEL'),
            'address' => Module::t('labels','PERSON_ADDRESS_LABEL'),
            'fio'  => Module::t('labels','PERSON_FIO_LABEL'),
            'first_name' => Module::t('labels','PERSON_FIRST_NAME_LABEL'),
            'second_name' => Module::t('labels','PERSON_SECOND_NAME_LABEL'),
            'patronymic' => Module::t('labels','PERSON_PATRONYMIC_LABEL'),
            'birth_year' => Module::t('labels','PERSON_BIRTH_YEAR_LABEL'),
            'photo_url' => Module::t('labels','PERSON_PHOTO_URL_LABEL'),
            'preview_url' => Module::t('labels','PERSON_PREVIEW_URL_LABEL'),


        ];
    }



}
