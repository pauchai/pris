<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\documents\models\DocumentQuery;
use vova07\users\Module;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\FilterValidator;


class PersonView extends  Person
{

    public static function tableName()
    {
        return "vw_person";
    }

    public static function primaryKey()
    {
        return ['__ident_id'];
    }

    public static function find()
    {
        return new PersonQuery(get_called_class());
    }

}
