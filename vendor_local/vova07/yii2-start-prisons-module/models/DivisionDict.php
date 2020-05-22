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


class DivisionDict extends  Model
{
    const ID_DIVISION_SOCIAL_REINTEGRATION = 1;
    const ID_DIVISION_FINANCE = 2;
    const ID_DIVISION_LOGISTIC = 3;
    const ID_DIVISION_ADMINISTRATION = 4;

    public $id;


    public static function getListForCombo()
    {

        return [
            self::ID_DIVISION_SOCIAL_REINTEGRATION => Module::t('default', 'ID_DIVISION_SOCIAL_REINTEGRATION'),
            self::ID_DIVISION_FINANCE => Module::t('default', 'ID_DIVISION_FINANCE'),
            self::ID_DIVISION_LOGISTIC => Module::t('default', 'ID_DIVISION_LOGISTIC'),
            self::ID_DIVISION_ADMINISTRATION => Module::t('default', 'ID_DIVISION_ADMINISTRATION'),

        ];
    }

    public function getTitle()
    {
        return self::getListForCombo()[$this->id];
    }


    



}
