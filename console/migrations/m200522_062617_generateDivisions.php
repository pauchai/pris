<?php

use yii\db\Migration;
use vova07\users\models\Officer;
use vova07\prisons\models\Division;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\DivisionDict;
/**
 * Class m200522_062617_OfficerDepartmentToDivision
 */
class m200522_062617_generateDivisions extends Migration
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
        $this->generateDivisionsFromDepartments();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach (Division::find()->all() as $division){
            $division->delete();
        }
        return true;
    }

    public function generateDivisionsFromDepartments()
    {
        $user = \vova07\users\models\User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
        foreach(CompanyDepartment::find()->all() as $companyDepartment)
        {
            $division = new Division([
                'company_id' => $companyDepartment->company_id,
                'division_id' => self::$departmentNameToDivisionId[$companyDepartment->department->title]

            ]);
            $division->title = $division->getDivisionDict()->title;
            if ($division->save() === false){
                $errors = $division->getErrors();
            };
        }
    }


}
