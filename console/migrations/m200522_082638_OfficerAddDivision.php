<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Department;
use vova07\prisons\models\DivisionDict;

/**
 * Class m200522_082638_OfficerAddDivision
 */
class m200522_082638_OfficerAddDivision extends Migration
{


    public static $departmentNameToDivisionId = [
        Department::SPECIAL_LOGISTIC => DivisionDict::ID_DIVISION_LOGISTIC,
        Department::SPECIAL_FINANCE => DivisionDict::ID_DIVISION_FINANCE,
        Department::SPECIAL_ADMINISTRATION => DivisionDict::ID_DIVISION_ADMINISTRATION,
        Department::SPECIAL_SOCIAL_REINTEGRATION => DivisionDict::ID_DIVISION_SOCIAL_REINTEGRATION
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Officer::tableName(), 'division_id',$this->tinyInteger(3));
        $this->setDevisionColumnResolvedFromDepartmentId();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Officer::tableName(),'division_id');

        return true;
    }

    public function setDevisionColumnResolvedFromDepartmentId()
    {
        $user = \vova07\users\models\User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
        foreach(Officer::find()->all() as $officer)
        {
            $officer->division_id = self::$departmentNameToDivisionId[$officer->department->title];
            if (!$officer->save()){
                $errors = $officer->getErrors();
            };

        }
    }

}
