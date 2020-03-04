<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:27
 */

namespace vova07\concepts\helpers;


use vova07\rbac\Module;
use vova07\users\models\User;

class Rbac
{
    public static function Add()
    {
        $auth = \Yii::$app->authManager;
        // Permissions
        $permissions = [
            Module::PERMISSION_CONCEPTS_LIST,
            Module::PERMISSION_CONCEPT_CREATE,
            Module::PERMISSION_CONCEPT_UPDATE,
            Module::PERMISSION_CONCEPT_DELETE,
            Module::PERMISSION_CONCEPT_VIEW,
            Module::PERMISSION_CONCEPT_CLASS_CREATE,
            Module::PERMISSION_CONCEPT_CLASS_DELETE,

            Module::PERMISSION_CONCEPT_PARTICIPANT_CREATE,
            Module::PERMISSION_CONCEPT_PARTICIPANT_DELETE,

            Module::PERMISSION_CONCEPT_VISIT_CREATE,
            Module::PERMISSION_CONCEPT_VISIT_DELETE,

        ];

        foreach($permissions as $permissionName){
            \vova07\rbac\helpers\Rbac::addPermission($permissionName);
            \vova07\rbac\helpers\Rbac::addChildToRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,$permissionName);


        }

        $permissions = [


        ];

    }
}