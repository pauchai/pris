<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:27
 */

namespace vova07\electricity\helpers;


use vova07\rbac\Module;
use vova07\users\models\User;

class Rbac
{
    public static function Add()
    {
        $auth = \Yii::$app->authManager;
        // Permissions
        $permissions = [
            Module::PERMISSION_ELECTRICITY_ACCESS,

        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);
        }

        $permissions = [

            Module::PERMISSION_ELECTRICITY_LIST,
            Module::PERMISSION_ELECTRICITY_CREATE,
            Module::PERMISSION_ELECTRICITY_UPDATE,
            Module::PERMISSION_ELECTRICITY_DELETE,
            Module::PERMISSION_ELECTRICITY_VIEW,
        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);
        }

        $permissions = [
            Module::PERMISSION_ELECTRICITY_BALANCE_IMPORT,

        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);


        }

        $permissions = [
            Module::PERMISSION_ELECTRICITY_SUMMARIZED_LIST,

        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);

        }
    }
}