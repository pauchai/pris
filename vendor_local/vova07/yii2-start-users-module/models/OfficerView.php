<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;


class OfficerView extends  Officer
{
    public static function tableName()
    {
        return 'vw_officer';
    }

    public static function find()
    {
        return new OfficerQuery(get_called_class());

    }


}
