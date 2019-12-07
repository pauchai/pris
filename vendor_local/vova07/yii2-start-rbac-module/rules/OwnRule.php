<?php

namespace vova07\rbac\rules;

use yii\rbac\Rule;

class OwnRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'own';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->ownableitem->created_by == $user : false;
    }
}
