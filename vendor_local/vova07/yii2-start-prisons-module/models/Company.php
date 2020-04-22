<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\Module;
use vova07\users\models\Officer;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
  * Class Company
 * @package vova07\prisons\models
 *
 * @property string $title Title
 */

class Company extends  Ownableitem
{

    const PRISON_PU1 = 'pu-1';
    const PRISON_PU2 = 'pu-2';
    const PRISON_PU3 = 'pu-3';
    const PRISON_PU4 = 'pu-4';
    const PRISON_PU5 = 'pu-5';
    const PRISON_PU6 = 'pu-6';
    const PRISON_PU7 = 'pu-7';
    const PRISON_PU8 = 'pu-8';
    const PRISON_PU9 = 'pu-9';
    const PRISON_PU10 = 'pu-10';
    const PRISON_PU11 = 'pu-11';
    const PRISON_PU12 = 'pu-12';
    const PRISON_PU13 = 'pu-13';
    const PRISON_PU14 = 'pu-14';
    const PRISON_PU15 = 'pu-15';
    const PRISON_PU16 = 'pu-16';
    const PRISON_PU17 = 'pu-17';
    const PRISON_PU18 = 'pu-18';
    const PRISON_DEPARTMENT = 'DEPARTMENT';

    public static function tableName()
    {
        return 'company';
    }
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['alias'], 'unique'],
            [['address', 'address2'], 'string']
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
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'alias' => Schema::TYPE_STRING . ' NOT NULL',
                'address' => Schema::TYPE_STRING ,
                'address2' => Schema::TYPE_STRING
            ],
            'indexes' => [
                [self::class, ['title'], true],
                [self::class, ['alias'], true],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'title',
                    'slugAttribute' => 'alias',

                    'ensureUnique' => true,
                ],
                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                        'departments',

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
        return new CompanyQuery(get_called_class());
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


    public function getDepartments()
    {
        return $this->hasMany(Department::class, ['__ownableitem_id' => 'department_id'])->viaTable('company_department', ['company_id' => '__ownableitem_id']);
        //return $this->hasMany(Company)
    }

    public function getCompanyDepartments()
    {
        return $this->hasMany(CompanyDepartment::class, ['company_id'=>'__ownableitem_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class, ['__company_Id' => '__ownableitem_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'__ownableitem_id','title');
    }

    public static function getList()
    {
        return [
            self::PRISON_PU1,
            self::PRISON_PU2,
            self::PRISON_PU3,
            self::PRISON_PU4,
            self::PRISON_PU5,
            self::PRISON_PU6,
            self::PRISON_PU7,
            self::PRISON_PU8,
            self::PRISON_PU9,
            self::PRISON_PU10,
            self::PRISON_PU11,
            self::PRISON_PU12,
            self::PRISON_PU13,
            self::PRISON_PU14,
            self::PRISON_PU15,
            self::PRISON_PU16,
            self::PRISON_PU17,
            self::PRISON_PU18,
            self::PRISON_DEPARTMENT,

        ];
    }
    public function attributeLabels()
    {
        return [
          'title' =>  Module::t('label','COMPANY_TITLE_LABEL'),
        ];
    }

    public function getOfficers()
    {
        return $this->hasMany(Officer::class,['company_id'=>'__ownableitem_id']);
    }


}
