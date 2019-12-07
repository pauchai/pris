<?php

namespace vova07\base\components;

use vova07\tasks\Module;
use vova07\rbac\rules\AuthorRule;
use Yii;
use yii\console\Controller;

/**
 * RBAC console controller.
 */
class ConsoleController extends Controller
{


    /**
     * @return \yii\rbac\ManagerInterface
     */
    public static function getAuth()
    {
        static $auth;
        if (!isset($auth)){
            $auth = Yii::$app->authManager;
        }
        return $auth;


    }

    public function createOrGetPermission($name)
    {

        $auth = self::getAuth();
        if (is_null( $permission = $auth->getPermission($name))){
            $permission = $auth->createPermission($name);
            $permission->description = Module::t('auth', $name);
            $auth->add($permission);
        }
        return $permission;

    }
}
