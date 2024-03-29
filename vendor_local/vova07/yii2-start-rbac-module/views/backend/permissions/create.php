<?php

/**
 * Permission create view.
 *
 * @var \yii\base\View $this View
 * @var \yii\base\DynamicModel $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 * @var array $permissionArray Permissions array
 * @var array $ruleArray Rules array
 */

use vova07\themes\adminlte2\widgets\Box;
use vova07\rbac\Module;

$this->title = Module::t('rbac', 'BACKEND_PERMISSIONS_CREATE_TITLE');
$this->params['subtitle'] = Module::t('rbac', 'BACKEND_PERMISSIONS_CREATE_SUBTITLE');
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
                    'class' => 'box-primary'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => '{cancel}'
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'permissionArray' => $permissionArray,
                'ruleArray' => $ruleArray,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>