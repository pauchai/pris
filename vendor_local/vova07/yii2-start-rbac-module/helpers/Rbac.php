<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.11.2019
 * Time: 14:27
 */

namespace vova07\rbac\helpers;


use vova07\rbac\Module;
use vova07\users\models\User;

class Rbac
{
    public static function init()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        // Permissions
        $accessBackend = $auth->createPermission(Module::PERMISSION_ACCESS_BACKEND);
        $accessBackend->description = 'Can access backend';
        $auth->add($accessBackend);

        $administrateRbac = $auth->createPermission(Module::PERMISSION_ADMINISTRATE_RBAC);
        $administrateRbac->description = 'Can administrate all "RBAC" module';
        $auth->add($administrateRbac);

        $BViewRoles = $auth->createPermission(Module::PERMISSION_VIEW_ROLES);
        $BViewRoles->description = 'Can view roles list';
        $auth->add($BViewRoles);

        $BCreateRoles = $auth->createPermission(Module::PERMISSION_CREATE_ROLES);
        $BCreateRoles->description = 'Can create roles';
        $auth->add($BCreateRoles);

        $BUpdateRoles = $auth->createPermission(Module::PERMISSION_UPDATE_ROLES);
        $BUpdateRoles->description = 'Can update roles';
        $auth->add($BUpdateRoles);

        $BDeleteRoles = $auth->createPermission(Module::PERMISSION_DELETE_ROLES);
        $BDeleteRoles->description = 'Can delete roles';
        $auth->add($BDeleteRoles);

        $BViewPermissions = $auth->createPermission(Module::PERMISSION_VIEW_PERMISSIONS);
        $BViewPermissions->description = 'Can view permissions list';
        $auth->add($BViewPermissions);

        $BCreatePermissions = $auth->createPermission(Module::PERMISSION_CREATE_PERMISSIONS);
        $BCreatePermissions->description = 'Can create permissions';
        $auth->add($BCreatePermissions);

        $BUpdatePermissions = $auth->createPermission(Module::PERMISSION_UPDATE_PERMISSIONS);
        $BUpdatePermissions->description = 'Can update permissions';
        $auth->add($BUpdatePermissions);

        $BDeletePermissions = $auth->createPermission(Module::PERMISSION_DELETE_PERMISSIONS);
        $BDeletePermissions->description = 'Can delete permissions';
        $auth->add($BDeletePermissions);

        $BViewRules = $auth->createPermission(Module::PERMISSION_VIEW_RULES);
        $BViewRules->description = 'Can view rules list';
        $auth->add($BViewRules);

        $BCreateRules = $auth->createPermission(Module::PERMISSION_CREATE_RULES);
        $BCreateRules->description = 'Can create rules';
        $auth->add($BCreateRules);

        $BUpdateRules = $auth->createPermission(Module::PERMISSION_UPDATE_RULES);
        $BUpdateRules->description = 'Can update rules';
        $auth->add($BUpdateRules);

        $BDeleteRules = $auth->createPermission(Module::PERMISSION_DELETE_RULES);
        $BDeleteRules->description = 'Can delete rules';
        $auth->add($BDeleteRules);

        // Assignments
        $auth->addChild($administrateRbac, $BViewRoles);
        $auth->addChild($administrateRbac, $BCreateRoles);
        $auth->addChild($administrateRbac, $BUpdateRoles);
        $auth->addChild($administrateRbac, $BDeleteRoles);
        $auth->addChild($administrateRbac, $BViewPermissions);
        $auth->addChild($administrateRbac, $BCreatePermissions);
        $auth->addChild($administrateRbac, $BUpdatePermissions);
        $auth->addChild($administrateRbac, $BDeletePermissions);
        $auth->addChild($administrateRbac, $BViewRules);
        $auth->addChild($administrateRbac, $BCreateRules);
        $auth->addChild($administrateRbac, $BUpdateRules);
        $auth->addChild($administrateRbac, $BDeleteRules);

        // Roles
        $socReintagrationDepartmentHead = $auth->createRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD);
        $auth->add($socReintagrationDepartmentHead);
        $socReintagrationDepartmentEducator = $auth->createRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR);
        $auth->add($socReintagrationDepartmentEducator);
        $socReintagrationDepartmentExpert = $auth->createRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT);
        $auth->add($socReintagrationDepartmentExpert);

        $auth->addChild($socReintagrationDepartmentEducator,$socReintagrationDepartmentHead );
        $auth->addChild($socReintagrationDepartmentHead, $socReintagrationDepartmentExpert);

        $socReintagrationDepartmentSociologist = $auth->createRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST);
        $auth->add($socReintagrationDepartmentSociologist);
        $socReintagrationDepartmentPsycologist = $auth->createRole(Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST);
        $auth->add($socReintagrationDepartmentPsycologist);
        $financeDepartmentHead = $auth->createRole(Module::ROLE_FINANCE_DEPARTMENT_HEAD);
        $auth->add($financeDepartmentHead);
        $financeDepartmentExpert = $auth->createRole(Module::ROLE_FINANCE_DEPARTMENT_EXPERT);
        $auth->add($financeDepartmentExpert);

        $logisticAndAdministrationDepartmentHead = $auth->createRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD);
        $auth->add($logisticAndAdministrationDepartmentHead);
        $logisticAndAdministrationDepartmentExpert = $auth->createRole(Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT);
        $auth->add($logisticAndAdministrationDepartmentExpert);


        $admin = $auth->createRole(Module::ROLE_ADMIN);
        $admin->description = 'Admin';
        $auth->add($admin);


        $superadmin = $auth->createRole(Module::ROLE_SUPERADMIN);
        $superadmin->description = 'Super admin';
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
        $auth->addChild($superadmin, $accessBackend);
        $auth->addChild($superadmin, $administrateRbac);
        $auth->addChild($superadmin, $socReintagrationDepartmentHead);
        $auth->addChild($superadmin, $socReintagrationDepartmentExpert);
        $auth->addChild($superadmin, $socReintagrationDepartmentPsycologist);
        $auth->addChild($superadmin, $socReintagrationDepartmentSociologist);
        $auth->addChild($superadmin, $socReintagrationDepartmentEducator);
        $auth->addChild($superadmin, $financeDepartmentExpert);
        $auth->addChild($superadmin, $financeDepartmentHead);
        $auth->addChild($superadmin, $logisticAndAdministrationDepartmentExpert);
        $auth->addChild($superadmin, $logisticAndAdministrationDepartmentHead);
        self::ReAssignUsersRoles();

    }

    public static function ReAssignUsersRoles()
    {
        $usersModule = \Yii::$app->getModule('users');
        $authManager = \Yii::$app->authManager;
        $users = User::find()->all();
        $authManager->removeAllAssignments();
        foreach ($users as $user)
        {
            $role = $authManager->getRole($user->role);
            $authManager->assign($role,$user->primaryKey);

        }
    }

    public static function addPermission($name)
    {
        $authManager = \Yii::$app->authManager;
        $description = Module::t('default',$name . '_DESCRIPTION');
        $permission = $authManager->createPermission($name,$description);
        $permission->description = $description;
        return $authManager->add($permission);
    }

    public static function addChildToRole($parentName, $childName)
    {
        $authManager = \Yii::$app->authManager;

        $parentRole = $authManager->getRole($parentName);
        $permission = $authManager->getPermission($childName);
        return $authManager->addChild($parentRole,$permission);
    }
    public static function addChildToPermission($parentName,$childName)
    {
        $authManager = \Yii::$app->authManager;

        $parentPermission = $authManager->getPermission($parentName);
        $permission = $authManager->getPermission($childName);
        return $authManager->addChild($parentPermission,$permission);
    }

    public static function checkAccess($permissionName)
    {
        return \Yii::$app->authManager->checkAccess(\Yii::$app->user->id,$permissionName);
    }
}