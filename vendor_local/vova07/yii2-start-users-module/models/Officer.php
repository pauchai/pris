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
use vova07\prisons\models\Rank;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
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
            [['company_id','division_id'],'required'],
            [['rank_id'],'integer'],

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
                'post_id' => $migration->integer(),
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
                [get_called_class(), ['company_id','division_id','post_id'],Post::class, ['company_id','division_id', '__ownableitem_id']],
                [get_called_class(), ['__ownableitem_id'],Post::class, ['__ownableitem_id']],
                [get_called_class(), 'rank_id',Rank::class, 'id']


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


    public function getPost()
    {
        return $this->hasOne(Post::class, ['__ownableitem_id' => 'post_id']);
    }

    public function getPostDict()
    {
        return new PostDict(['id' => $this->post_id]);
    }

    public function getRank()
    {
        return new Rank(['id' => $this->rank_id]);

    }

}
