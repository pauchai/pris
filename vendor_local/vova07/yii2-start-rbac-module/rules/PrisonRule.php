<?php

namespace vova07\rbac\rules;

use yii\rbac\Rule;

class PrisonRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'prison';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {

        $prison_id = Yii::$app->user->identity->officer->prison_id;

        return isset($params['model']) ? $params['model']->ownableitem->created_by == $user : false;
    }
}
