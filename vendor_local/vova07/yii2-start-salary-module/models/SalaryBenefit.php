<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Ownableitem;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\base\Model;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class SalaryBenefit extends  Model
{
    const EXTRA_CLASS_POINT_0TO2_ANI = 0;
    const EXTRA_CLASS_POINT_2TO5_ANI = 2;
    const EXTRA_CLASS_POINT_5TO10_ANI = 3;
    const EXTRA_CLASS_POINT_10TO15_ANI = 4;
    const EXTRA_CLASS_POINT_15TO20_ANI = 5;
    const EXTRA_CLASS_POINT_OVER20_ANI = 6;

    public $value;
   public static function getListForCombo()
   {
       return [
           self::EXTRA_CLASS_POINT_0TO2_ANI => Module::t('default','EXTRA_CLASS_POINT_0TO2_ANI'),
           self::EXTRA_CLASS_POINT_2TO5_ANI => Module::t('default','EXTRA_CLASS_POINT_2TO5_ANI'),
           self::EXTRA_CLASS_POINT_5TO10_ANI => Module::t('default','EXTRA_CLASS_POINT_5TO10_ANI'),
           self::EXTRA_CLASS_POINT_10TO15_ANI => Module::t('default','EXTRA_CLASS_POINT_10TO15_ANI'),
           self::EXTRA_CLASS_POINT_15TO20_ANI => Module::t('default','EXTRA_CLASS_POINT_15TO20_ANI'),
           self::EXTRA_CLASS_POINT_OVER20_ANI => Module::t('default','EXTRA_CLASS_POINT_OVER20_ANI'),

       ];
   }
   public function getTitle()
   {
       return self::getListForCombo()[$this->value];
   }





}
