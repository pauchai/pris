<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:30
 */

namespace vova07\users\helpers;


use vova07\rbac\Module;

class Rbac
{
    public static function add()
    {
        $auth = \Yii::$app->authManager;

        // Permissions
        $permissions = [
            Module::PERMISSION_PRISONERS_LIST,

        ];


        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
        }
    }

}