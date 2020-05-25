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
use yii\base\Model;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class PostDict extends  Model
{
  const POST_COMPANY_HEAD = 1;
  const POST_COMPANY_HEAD_ASSISTENT = 2;
  const POST_EDUCATOR = 3;


    public $id;


    public static function getListForCombo()
    {

        return [
            self::POST_COMPANY_HEAD => Module::t('default', 'POST_COMPANY_HEAD'),
            self::POST_COMPANY_HEAD_ASSISTENT => Module::t('default', 'POST_COMPANY_HEAD_ASSISTENT'),
            self::POST_EDUCATOR => Module::t('default', 'POST_EDUCATOR'),


        ];
    }

    public function getTitle()
    {
        return self::getListForCombo()[$this->id];
    }


    



}
