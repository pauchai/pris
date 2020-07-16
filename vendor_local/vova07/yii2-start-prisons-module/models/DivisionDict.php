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
    const ID_DIVISION_JURISTIC = 5;
    const ID_DIVISION_PRISONER_ACCOUNTING = 6;
    const ID_DIVISION_SECRETARIAT = 7;
    const ID_DIVISION_SECURITY_REGIME = 8;
    const ID_DIVISION_GUARD_ESCORT = 9;
   // const ID_DIVISION_ESCORT = 10;
    const ID_DIVISION_IT = 11;
    const ID_DIVISION_PROCURMENT = 12;
    const ID_DIVISION_MEDICAL = 13;
    const ID_DIVISION_STAFF = 14;
    const ID_DIVISION_MISC = 127;

    public $id;


    public static function getListForCombo()
    {

        return [
            self::ID_DIVISION_SOCIAL_REINTEGRATION => Module::t('default', 'ID_DIVISION_SOCIAL_REINTEGRATION'),
            self::ID_DIVISION_FINANCE => Module::t('default', 'ID_DIVISION_FINANCE'),
            self::ID_DIVISION_LOGISTIC => Module::t('default', 'ID_DIVISION_LOGISTIC'),
            self::ID_DIVISION_ADMINISTRATION => Module::t('default', 'ID_DIVISION_ADMINISTRATION'),
            self::ID_DIVISION_JURISTIC => Module::t('default', 'ID_DIVISION_JURISTIC'),
            self::ID_DIVISION_PRISONER_ACCOUNTING => Module::t('default', 'ID_DIVISION_PRISONER_ACCOUNTING'),
            self::ID_DIVISION_SECRETARIAT => Module::t('default', 'ID_DIVISION_SECRETARIAT'),
            self::ID_DIVISION_SECURITY_REGIME => Module::t('default', 'ID_DIVISION_SECURITY_REGIME'),
            self::ID_DIVISION_GUARD_ESCORT => Module::t('default', 'ID_DIVISION_GUARD_ESCORT'),
           // self::ID_DIVISION_ESCORT => Module::t('default', 'ID_DIVISION_ESCORT'),
            self::ID_DIVISION_IT => Module::t('default', 'ID_DIVISION_IT'),
            self::ID_DIVISION_PROCURMENT => Module::t('default', 'ID_DIVISION_PROCURMENT'),
            self::ID_DIVISION_MEDICAL => Module::t('default', 'ID_DIVISION_MEDICAL'),
            self::ID_DIVISION_STAFF => Module::t('default', 'ID_DIVISION_STAFF'),
            self::ID_DIVISION_MISC => Module::t('default', 'ID_DIVISION_MISC'),


        ];
    }

    public function getTitle()
    {
        return self::getListForCombo()[$this->id];
    }


    



}
