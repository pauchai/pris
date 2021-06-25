<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\psycho\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_PSYCHO_LIST,


         //  Module::PERMISSION_PSYCHO_TESTS,

       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);

        }

       $permissions = [
           Module::PERMISSION_PSYCHO_LIST,
            Module::PERMISSION_PSYCHO_CREATE,
           Module::PERMISSION_PSYCHO_UPDATE,
           Module::PERMISSION_PSYCHO_DELETE,

             Module::PERMISSION_PSYCHO_TESTS,

       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);
       }


   }
}