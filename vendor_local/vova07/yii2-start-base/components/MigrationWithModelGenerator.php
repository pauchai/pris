<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/5/20
 * Time: 7:55 AM
 */

namespace vova07\base\components;


use vova07\base\ModelGenerator\ModelTableGenerator;
use yii\db\Migration;

abstract class MigrationWithModelGenerator extends Migration
{
    public $models;

    protected function generateModelTables()
    {
        foreach ($this->models as $modelClassName)
            $modelsGenerated = (new ModelTableGenerator())->generateTable($modelClassName);
    }

    protected function dropModelTables()
    {
        \vova07\base\ModelGenerator\Helper::dropTablesForModels($this->models);
    }

    protected function login($userName = 'admin')
    {
        $user = \vova07\users\models\User::findOne(['username' => $userName]);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
    }
}