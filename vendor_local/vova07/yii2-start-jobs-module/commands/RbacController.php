<?php

namespace vova07\jobs\commands;


use vova07\base\commands\ConsoleController;
use vova07\tasks\Module;
use vova07\rbac\rules\AuthorRule;
use Yii;

use vova07\rbac\Module as RbacModule ;

/**
 * RBAC console controller.
 */
class RbacController extends ConsoleController
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'add';

    /**
     * Initial RBAC action.
     */
    public function actionAdd()
    {
        $auth = self::getAuth();

             // Permissions

     // $auth->addChild($auth->getRole(RbacModule::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD),
     //      $this->createOrGetPermission(Module::PERMISSION_COMMITEE_)
     // );



    }


}
