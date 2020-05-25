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
use vova07\prisons\models\Division;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostDict;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Officer extends  OwnableItem
{
    const RANK_CHESTOR_DE_JUSTITIE = 1;
    const RANK_COMISAR_SEF_DE_JUSTITIE = 2;
    const RANK_COMISAR_PRINCIPAL_DE_JUSTITIE = 3;
    const RANK_COMISAR_DE_JUSTITIE = 4;
    const RANK_INSPECTOR_PRINCIPAL_DE_JUSTITIE = 5;
    const RANK_INSPECTOR_SUPERIOR_DE_JUSTITIE = 6;
    const RANK_INSPECTOR_DE_JUSTITIE = 7;
    const RANK_AGENT_SEF_PRINCIPAL_DE_JUSTITIE = 8;
    const RANK_AGENT_SEF_DE_JUSTITIE = 9;
    const RANK_AGENT_SEF_ADJUNCT_DE_JUSTITIE = 10;
    const RANK_AGENT_PRINCIPAL_DE_JUSTITIE = 11;
    const RANK_AGENT_SUPERIOR_DE_JUSTITIE = 12;

    use SaveRelationsTrait;



    public static function tableName()
    {
        return 'officer';
    }

    public function rules()
    {
        return [
            [['company_id','division_id'],'required'],
            [['rank_id', 'post_id'],'integer'],

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
                'company_id' => Schema::TYPE_INTEGER,
                'division_id' => Schema::TYPE_INTEGER,
                'rank_id' => Schema::TYPE_TINYINT,
                'post_id' => $migration->tinyInteger(3),
                'status_id' => Schema::TYPE_TINYINT,

            ],
            'dependsOn' => [
                Person::class
            ],
            'indexes' => [
                [self::class, 'rank_id'],

            ],
            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()],
                [get_called_class(), ['company_id','division_id'],Division::class, ['company_id','division_id']],
                [get_called_class(), ['company_id','division_id','post_id'],Post::class, ['company_id','division_id', 'post_id']]

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
                    'division'
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
    public function getDivision()
    {
        return $this->hasOne(Division::class,['company_id' => 'company_id', 'division_id' => 'division_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class,[ '__ident_id'  => '__person_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->select(['__person_id','fio'=>'CONCAT(person.second_name, " ", person.first_name," " , person.patronymic)' ])->joinWith('person')->asArray()->all(),'__person_id','fio');
    }

    public static  function getRanksForCombo()
    {
        return [
            self::RANK_CHESTOR_DE_JUSTITIE => Module::t('default','RANK_CHESTOR_DE_JUSTITIE'),
            self::RANK_COMISAR_SEF_DE_JUSTITIE => Module::t('default','RANK_COMISAR_SEF_DE_JUSTITIE'),
            self::RANK_COMISAR_PRINCIPAL_DE_JUSTITIE => Module::t('default','RANK_COMISAR_PRINCIPAL_DE_JUSTITIE'),
            self::RANK_COMISAR_DE_JUSTITIE => Module::t('default','RANK_COMISAR_DE_JUSTITIE'),
            self::RANK_INSPECTOR_PRINCIPAL_DE_JUSTITIE => Module::t('default','RANK_INSPECTOR_PRINCIPAL_DE_JUSTITIE'),
            self::RANK_INSPECTOR_SUPERIOR_DE_JUSTITIE => Module::t('default','RANK_INSPECTOR_SUPERIOR_DE_JUSTITIE'),
            self::RANK_INSPECTOR_DE_JUSTITIE => Module::t('default','RANK_INSPECTOR_DE_JUSTITIE'),
            self::RANK_AGENT_SEF_PRINCIPAL_DE_JUSTITIE => Module::t('default','RANK_AGENT_SEF_PRINCIPAL_DE_JUSTITIE'),
            self::RANK_AGENT_SEF_DE_JUSTITIE => Module::t('default','RANK_AGENT_SEF_DE_JUSTITIE'),
            self::RANK_AGENT_SEF_ADJUNCT_DE_JUSTITIE => Module::t('default','RANK_AGENT_SEF_ADJUNCT_DE_JUSTITIE'),
            self::RANK_AGENT_PRINCIPAL_DE_JUSTITIE => Module::t('default','RANK_AGENT_PRINCIPAL_DE_JUSTITIE'),
            self::RANK_AGENT_SUPERIOR_DE_JUSTITIE => Module::t('default','RANK_AGENT_SUPERIOR_DE_JUSTITIE'),
        ];


    }
    public function getRank()
    {
        if ($this->rank_id)
            return self::getRanksForCombo()[$this->rank_id];
        else
            return null;
    }

    public function getPost()
    {
        return $this->hasOne(Post::class, ['company_id' => 'company_id', 'division_id' => 'division_id', 'post_id' => 'post_id']);
    }

    public function getPostDict()
    {
        return new PostDict(['id' => $this->post_id]);
    }

}
