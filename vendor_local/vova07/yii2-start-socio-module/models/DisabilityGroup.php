<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models;




use yii\base\Model;
use vova07\socio\Module;

class DisabilityGroup extends  Model
{

    const ID_GROUP_I = 1;
    const ID_GROUP_II = 2;
    const ID_GROUP_III = 3;

    public $id;


    public static function getListForCombo()
    {
        return [
            self::ID_GROUP_I => Module::t('default','DISABILITY_GROUP_I'),
            self::ID_GROUP_II => Module::t('default','DISABILITY_GROUP_II'),
            self::ID_GROUP_III => Module::t('default','DISABILITY_GROUP_III'),
        ];
    }

    public function getTitle()
    {
        return self::getListForCombo()[$this->id];
    }

    public static function build($groupId){
        static $groups = [];
        if (key_exists($groupId, $groups))
        {
            return $groups[$groupId];
        } else {
            $groups[$groupId] = new self(['id' => $groupId]);
        }

        return $groups[$groupId];
    }





}
