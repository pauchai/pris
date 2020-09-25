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
use vova07\prisons\Module;
use vova07\rbac\models\Rule;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryBenefit;
use vova07\salary\models\SalaryClass;
use vova07\users\models\Officer;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class OfficerPostView extends  ActiveRecord
{
    public static function tableName()
    {
        return 'vw_officer_post';
    }

    public static function find()
    {
        return new OfficerPostViewQuery(get_called_class());

    }

    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id' => 'officer_id']);
    }
    public function getOfficerPost()
    {
        return $this->hasOne(OfficerPost::class, [
            'officer_id' => 'officer_id',
            'company_id' => 'company_id',
            'division_id' => 'division_id',
            'postdict_id'=>'postdict_id'
        ]);
    }

    public static function primaryKey()
    {
        return [
            'company_id', 'officer_id', 'division_id', 'postdict_id'
        ] ;
    }

    public function getRanks()
    {
        return $this->hasMany(Rank::class,['id' => 'rank_id']);
    }

    public function attributeLabels()
    {
        return [
            'category_id' => Module::t('labels', 'OFFICER_POST_VIEW_CATEGORY_ID')
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::class, ['company_id' => 'company_id','division_id' => 'division_id','postdict_id'=>'postdict_id']);
    }


}
