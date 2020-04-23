<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\plans\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_PRISONER_PLAN_VIEW,
           Module::PERMISSION_PROGRAMS_SUMMARIZED_LIST

       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
        }

       $permissions = [

           Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING,
           Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);

       }

       $permissions = [

           Module::PERMISSION_PROGRAM_PLANING_LIST,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
       }


       $permissions = [
           Module::PERMISSION_PROGRAM_PRISONERS_COMMENT_CREATE,
           Module::PERMISSION_PRISONER_PLAN_COMMENT_CREATE,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
       }

       $permissions = [
           Module::PERMISSION_PROGRAM_DELETE,




       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
       }

       $permissions = [
           Module::PERMISSION_PROGRAM_CREATE,
           Module::PERMISSION_PROGRAM_UPDATE,
           Module::PERMISSION_PROGRAM_LIST,



       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
       }
}
}