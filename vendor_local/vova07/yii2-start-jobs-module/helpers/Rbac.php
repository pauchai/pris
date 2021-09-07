<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\jobs\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_JOBS_ACCESS,

           Module::PERMISSION_GENERAL_LIST_VIEW,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
//           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);

       }
       $permissions = [
           Module::PERMISSION_JOBS_ACCESS,
           Module::PERMISSION_PAID_JOBS_LIST,
           Module::PERMISSION_PAID_JOB_VIEW,
           Module::PERMISSION_PAID_JOB_CREATE,
           Module::PERMISSION_PAID_JOB_UPDATE,
           Module::PERMISSION_PAID_JOB_DELETE
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);


       }


       $permissions = [
           Module::PERMISSION_PAID_JOBS_LIST,
           Module::PERMISSION_PAID_JOB_VIEW,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT,$permissionName);
//           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);

       }

       $permissions = [

           Module::PERMISSION_NOT_PAID_JOBS_LIST,
           Module::PERMISSION_NOT_PAID_JOB_CREATE,
           Module::PERMISSION_NOT_PAID_JOB_UPDATE,
           Module::PERMISSION_NOT_PAID_JOB_DELETE,
           Module::PERMISSION_NOT_PAID_JOB_VIEW,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);

       }

       $permissions = [
           Module::PERMISSION_NOT_PAID_TYPE_LIST,
           Module::PERMISSION_PAID_TYPE_LIST,


       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,$permissionName);

       }

   }
}