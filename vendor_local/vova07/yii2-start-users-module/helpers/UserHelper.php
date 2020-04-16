<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/15/20
 * Time: 10:08 AM
 */

namespace vova07\users\helpers;


use vova07\users\models\User;

class UserHelper
{
    public static function getUserIdsByRolesQuery($roles)
    {
        $query = User::find()->select(['__ident_id']);

        foreach ($roles as $role ){
            $query->orWhere([
                'role' => $role,
            ]);
        }
        return $query;
    }
}