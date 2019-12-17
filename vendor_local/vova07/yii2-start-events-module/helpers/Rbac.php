<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:27
 */

namespace vova07\events\helpers;


use vova07\rbac\Module;
use vova07\users\models\User;

class Rbac
{
    public static function Add()
    {
        $auth = \Yii::$app->authManager;
        // Permissions
        $permissions = [
            Module::PERMISSION_EVENT_PLANING_LIST,
            Module::PERMISSION_EVENT_PLANING_CREATE,
            Module::PERMISSION_EVENT_PLANING_UPDATE,
            Module::PERMISSION_EVENT_PLANING_DELETE,
            Module::PERMISSION_EVENT_PLANING_VIEW,

        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);


        }

        $permissions = [


        ];

    }
}