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
            Module::PERMISSION_PRISONERS_VIEW,
            Module::PERMISSION_PRISONERS_UPDATE,
            Module::PERMISSION_PRISONERS_DELETE,
            Module::PERMISSION_PRISONERS_CREATE,
            Module::PERMISSION_PRISONERS_CELLS,


        ];

        foreach($permissions as $permissionName) {
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);

        }

        $permissions = [
            Module::PERMISSION_PRISONERS_LIST,
            Module::PERMISSION_PRISONERS_VIEW,
            Module::PERMISSION_PRISONERS_UPDATE,
            Module::PERMISSION_PRISONERS_CREATE,
            Module::PERMISSION_PRISONERS_CELLS,


        ];


        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
        }

        $permissions = [
            Module::PERMISSION_PRISONERS_LIST,
            Module::PERMISSION_PRISONERS_VIEW,


        ];
        foreach($permissions as $permissionName) {

            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR, $permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);

        }

        \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_QUICK_SWITCH_USER);
        \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SUPERADMIN,Module::PERMISSION_QUICK_SWITCH_USER);
    }

}