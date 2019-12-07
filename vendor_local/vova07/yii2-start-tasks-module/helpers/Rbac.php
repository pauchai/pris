<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:27
 */

namespace vova07\tasks\helpers;


use vova07\rbac\Module;
use vova07\users\models\User;

class Rbac
{
    public static function AddCommittee()
    {
        $auth = \Yii::$app->authManager;
        // Permissions
        $permissions = [
            Module::PERMISSION_COMMITTIEE_LIST,
            Module::PERMISSION_COMMITTIEE_VIEW,
            Module::PERMISSION_COMMITTIEE_CREATE,
            Module::PERMISSION_COMMITTIEE_UPDATE,
            Module::PERMISSION_COMMITTIEE_DELETE,
        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,$permissionName);
        }
    }
}