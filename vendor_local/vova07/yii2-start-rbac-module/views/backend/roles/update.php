<?php

/**
 * Role update view.
 *
 * @var \yii\base\View $this View
 * @var \yii\base\DynamicModel $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 * @var array $roleArray Roles array
 * @var array $ruleArray Rules array
 * @var array $permissionArray Permissions array
 */

use vova07\themes\adminlte2\widgets\Box;
use vova07\rbac\Module;

$this->title = Module::t('rbac', 'BACKEND_ROLES_UPDATE_TITLE');
$this->params['subtitle'] = Module::t('rbac', 'BACKEND_ROLES_UPDATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
]; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-success'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'deleteParam' => 'name',
                'buttonsTemplate' => '{create} {cancel} {delete}'
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'roleArray' => $roleArray,
                'ruleArray' => $ruleArray,
                'permissionArray' => $permissionArray,
                'box' => $box,
                'update' => true
            ]
        );
        Box::end(); ?>
    </div>
</div>
