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
    public $models = [
        Division::class,

    ];
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
        $this->generateModelTables();
        $this->generateDivisionsFromDepartments();
        $this->generateOtherDivisions();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach (Division::find()->all() as $division){
            $division->delete();
        }
        $this->dropModelTables();
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

    public function generateOtherDivisions()
    {
        $user = \vova07\users\models\User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);

        $baseDivisionIds = array_values(self::$departmentNameToDivisionId);

        foreach(Company::find()->all() as $company){
            foreach (DivisionDict::getListForCombo() as $divisionId => $divisionTitle)
            {
                if (\yii\helpers\ArrayHelper::isIn($divisionId, $baseDivisionIds))
                    continue;

                $division = new Division([
                    'company_id' => $company->primaryKey,
                    'division_id' => $divisionId,

                ]);
                $division->title = $division->getDivisionDict()->title;

                if ($division->save() === false){
                    $errors = $division->getErrors();
                };
            }
        }
    }


    private function generateModelTables()
    {
        foreach ($this->models as $modelClassName)
            $modelsGenerated = (new \vova07\base\ModelGenerator\ModelTableGenerator())->generateTable($modelClassName);
    }
    private function dropModelTables()
    {
        \vova07\base\ModelGenerator\Helper::dropTablesForModels($this->models);
    }


}
