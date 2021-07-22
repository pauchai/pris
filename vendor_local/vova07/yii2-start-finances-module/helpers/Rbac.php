<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\finances\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions

       \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_FINANCES_LIST);
       \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_FINANCES_VIEW);
       \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY);
       \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_FINANCES_ACCESS);
       \vova07\rbac\helpers\Rbac::addPermission(Module::PERMISSION_FINANCES_LIST_DEPS_BY_RANG);



       \vova07\rbac\helpers\Rbac::addChildToPermission(Module::PERMISSION_FINANCES_LIST, Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY );

       $permissions = [
           Module::PERMISSION_FINANCES_ACCESS,
           Module::PERMISSION_FINANCES_LIST,
           Module::PERMISSION_FINANCES_VIEW,
           Module::PERMISSION_FINANCES_LIST_DEPS_BY_RANG,

           // Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY,


       ];

       foreach($permissions as $permissionName){
          \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);

       }

       $permissions = [
           Module::PERMISSION_FINANCES_ACCESS,
           Module::PERMISSION_FINANCES_LIST_DEPS_BY_RANG,
           // Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);


       }
       $permissions = [
           Module::PERMISSION_FINANCES_ACCESS,
           Module::PERMISSION_FINANCES_VIEW,
           Module::PERMISSION_FINANCES_LIST_DEPS_BY_RANG,
           // Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
       }

       //\vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY);
       //\vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,Module::PERMISSION_FINANCES_LIST_REMAIN_ONLY);



   }
}