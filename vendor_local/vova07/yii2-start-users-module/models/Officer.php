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
use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Department;
use vova07\users\models\Person;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Officer extends  OwnableItem
{
    use SaveRelationsTrait;


    public static function tableName()
    {
        return 'officer';
    }

    public function rules()
    {
        return [
            [['company_id','department_id'],'required']
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
                'company_id' => Schema::TYPE_INTEGER,
                'department_id' => Schema::TYPE_INTEGER,
                'status_id' => Schema::TYPE_TINYINT,

            ],
            'dependsOn' => [
                Person::class
            ],
            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()],
                [get_called_class(), ['company_id','department_id'],CompanyDepartment::class, ['company_id','department_id']]
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
                    'person',
                    'ownableitem',
                    'company',
                    'department'
                ],
            ]
        ];

    }

    public static function find()
    {
        return new OfficerQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ident_id'  => '__person_id']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getCompany()
    {
        return $this->hasOne(Company::class,['__ownableitem_id' => 'company_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::class,['__ownableitem_id' => 'department_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class,[ '__ident_id'  => '__person_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->select(['__person_id','fio'=>'CONCAT(person.second_name, " ", person.first_name," " , person.patronymic)' ])->joinWith('person')->asArray()->all(),'__person_id','fio');
    }

}
