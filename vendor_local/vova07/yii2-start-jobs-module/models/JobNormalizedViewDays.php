<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\jobs\models;


use kartik\form\ActiveForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\finances\models\Balance;
use vova07\jobs\Module;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Prison;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\QueryBuilder;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\RequiredValidator;

class JobNormalizedViewDays extends  ActiveRecord
{
    const CATEGORY_PAID = 1;
    const CATEGORY_NOT_PAID = 2;

    public $category_id;




    public static function tableName()
    {
        return 'vw_jobs_normilized_days';
    }
    public static function primaryKey()
    {
        return ['prison_id','prisoner_id', 'type_id', 'category_id'];
    }
    public static function find()
    {
        return new JobNormalizedViewDaysQuery(get_called_class());
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ownableitem_id'=>'prisoner_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id'=>'prisoner_id']);
    }

    public static  function  getCategoriesForCombo($id = null)
    {
        $ret = [
            self::CATEGORY_PAID => Module::t('default','TYPE_PAID_LABEL'),
            self::CATEGORY_NOT_PAID => Module::t('default','TYPE_NOT_PAID_LABEL'),
        ];
        if (is_null($id))
            return $ret;
        else
            return $ret[$id];
    }

    public function getCategory()
    {
       self::getCategoriesForCombo($this->category_id);
    }

    public function getNewBieByYearForCategoryId($date, $category_id)
    {
        $year = \DateTime::createFromFormat('Y-m-d', $date)->format('Y');
        $query = self::find()->andWhere(['=', new Expression('YEAR(at)') ,  $year]);
        $query = $query->andWhere(new Expression('not isnull(hours)'));
        $query->andWhere([ 'prison_id' => \Yii::$app->base->company->primaryKey]);
        $query->andWhere([ 'category_id' => $category_id]);
        $query->groupBy(['prisoner_id']);
        $query->select(['cnt' => 'count(*)']);
        $query->having(['<=', 'cnt', 1]);

       // print_r(\Yii::$app->db->getQueryBuilder()->build($query));
        //return 1;
        return $query->count();
    }



}