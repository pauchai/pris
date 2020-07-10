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
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\jobs\helpers\Calendar;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryBenefit;
use vova07\salary\models\SalaryClass;
use vova07\users\models\Officer;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class OfficerPost extends  ActiveRecordMetaModel
{
    public static function tableName()
    {
        return 'officer_posts';
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
                'officer_id' => $migration->integer()->notNull(),
                'company_id' => $migration->integer()->notNull(),
                'division_id' => $migration->tinyInteger()->notNull(),
                'postdict_id' => $migration->smallInteger(),
                'full_time' => $migration->boolean()->defaultValue(true),
                'benefit_class' => $migration->tinyInteger(3)->notNull()->defaultValue(SalaryBenefit::EXTRA_CLASS_POINT_0TO2_ANI),
                'title' => $migration->string(),
                'rbac_role' => $migration->string(),
            ],
            'primaries' => [
                [self::class, ['officer_id', 'company_id','division_id','postdict_id']]
            ],

            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()],
                [get_called_class(), ['company_id','division_id'],Division::class,Division::primaryKey()],
                [get_called_class(), ['company_id', 'division_id', 'postdict_id'], Post::class, Post::primaryKey()],
                [get_called_class(), ['postdict_id'], PostDict::class, PostDict::primaryKey()],
                [get_called_class(), 'officer_id', Officer::class, Officer::primaryKey()],

            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function rules()
    {
        return [
            [['officer_id', 'company_id', 'division_id', 'postdict_id'],'required'],
            [['full_time'],'boolean'],
            [['benefit_class'], 'integer'],
            [['benefit_class'], 'default', 'value' => SalaryBenefit::EXTRA_CLASS_POINT_0TO2_ANI],
            [['title'], 'string'],
           // [['company_id', 'title'],'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                   // 'ownableitem',
                ],
            ]
        ];
    }

    public static function find()
    {
        return new OfficerPostQuery(get_called_class());
    }




    public  function getPostDict()
    {
        return $this->hasOne(PostDict::class,['id' => 'postdict_id']);

    }

    public function getCompany()
    {
        return $this->hasOne(Company::class, ['__ownableitem_id' => 'company_id']);
    }
    public function getDivision()
    {
        return $this->hasOne(Division::class, [
            'company_id' => 'company_id',
            'division_id' => 'division_id',
            ]);
    }
    public function getOfficer()
    {
        return $this->hasOne(Officer::class, ['__person_id' => 'officer_id']);
    }


    public function getTimeRate()
    {
        return $this->full_time?1:0.5;
    }
    public function getBenefitClass()
    {
        return (new SalaryBenefit([
            'value' => $this->benefit_class
        ]));
    }

    /**
     * @return bool
     */
    public function getIsMain()
    {
        $officerMainPost =  $this->officer->officerPost;

       return isset($officerMainPost) &&
            $officerMainPost->officer_id == $this->officer_id &&
            $officerMainPost->company_id == $this->company_id &&
            $officerMainPost->division_id == $this->division_id &&
            $officerMainPost->postdict_id == $this->postdict_id;
    }


}
