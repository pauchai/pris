<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\humanitarians\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_HUMANITARIAN_LIST,
           Module::PERMISSION_HUMANITARIAN_VIEW,
           Module::PERMISSION_HUMANITARIAN_CREATE,
           Module::PERMISSION_HUMANITARIAN_UPDATE,
           Module::PERMISSION_HUMANITARIAN_DELETE,

       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
        }

       // Permissions
       $permissions = [
           Module::PERMISSION_HUMANITARIAN_LIST,
           Module::PERMISSION_HUMANITARIAN_VIEW,
           Module::PERMISSION_HUMANITARIAN_CREATE,
           Module::PERMISSION_HUMANITARIAN_UPDATE,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
       }

   }
}