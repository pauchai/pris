<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use vova07\users\models\PersonViewQuery;
use yii\helpers\ArrayHelper;


class PersonView extends  Person
{
    public function rules()
    {
        $rules = ArrayHelper::merge(
            [
            [['fio'], 'string'],
            ],
            parent::rules() );
        return $rules;
    }

    public static function tableName()
    {
        return "vw_person";
    }

    public static function primaryKey()
    {
        return ['__ownableitem_id'];
    }

    public static function find()
    {
        return new PersonViewQuery(get_called_class());
    }

}
