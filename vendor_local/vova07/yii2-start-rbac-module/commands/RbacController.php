<?php

namespace vova07\rbac\commands;

use vova07\documents\helpers\Rbac;
use yii\console\Controller;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';

    /**
     * Initial RBAC action.
     */
    public function actionInit()
    {
       \vova07\rbac\helpers\Rbac::init();
       \vova07\users\helpers\Rbac::add();
       \vova07\events\helpers\Rbac::add();
       \vova07\prisons\helpers\Rbac::AddPrisonsSecurity();
       \vova07\tasks\helpers\Rbac::AddCommittee();
       \vova07\plans\helpers\Rbac::Add();
       \vova07\documents\helpers\Rbac::Add();
       \vova07\humanitarians\helpers\Rbac::Add();
       \vova07\jobs\helpers\Rbac::Add();
       \vova07\finances\helpers\Rbac::Add();
       \vova07\electricity\helpers\Rbac::Add();
       \vova07\psycho\helpers\Rbac::Add();

    }

}
