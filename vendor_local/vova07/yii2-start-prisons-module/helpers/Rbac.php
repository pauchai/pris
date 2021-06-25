<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\prisons\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function AddPrisonsSecurity()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_PRISONERS_SECURITY_LIST,
           Module::PERMISSION_PRISONERS_SECURITY_VIEW,
           Module::PERMISSION_PRISONERS_SECURITY_CREATE,
           Module::PERMISSION_PRISONERS_SECURITY_UPDATE,
           Module::PERMISSION_PRISONERS_SECURITY_DELETE,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
        }
       $permissions = [
           Module::PERMISSION_PRISONERS_SECURITY_LIST,
           Module::PERMISSION_PRISONERS_SECURITY_VIEW,
           Module::PERMISSION_PRISONERS_SECURITY_CREATE,
           Module::PERMISSION_PRISONERS_SECURITY_UPDATE,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
       }


       // Permissions
       $permissions = [
           Module::PERMISSION_PENALTIES_LIST,
           Module::PERMISSION_PENALTY_CREATE,
           Module::PERMISSION_PENALTY_VIEW,
           Module::PERMISSION_PENALTY_UPDATE,

       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);

       }
   }
}