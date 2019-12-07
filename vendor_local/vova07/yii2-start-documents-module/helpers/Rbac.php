<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:24
 */

namespace vova07\documents\helpers;


use vova07\rbac\Module;

class Rbac
{
   public static function add()
   {
       $auth =    $auth = \Yii::$app->authManager;

       // Permissions
       $permissions = [
           Module::PERMISSION_DOCUMENTS_LIST,
           Module::PERMISSION_DOCUMENT_CREATE,
           Module::PERMISSION_DOCUMENT_UPDATE,
           Module::PERMISSION_DOCUMENT_DELETE,
           Module::PERMISSION_DOCUMENT_VIEW,
       ];

       foreach($permissions as $permissionName){
           \vova07\rbac\helpers\Rbac::addPermission($permissionName);
           \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,$permissionName);
        }

   }
}