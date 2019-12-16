<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\EventSearch;
use vova07\plans\models\backend\ProgramDictSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Event;
use vova07\plans\models\EventParticipant;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\Requirement;
use vova07\plans\models\RequirementsQuery;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use vova07\users\models\Prisoner;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','index-print'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW],
            ]
        ];
        return $behaviors;
    }

    public function actionIndex($prisoner_id)
    {
        if (!($prisoner = Prisoner::findOne($prisoner_id))){
            throw new NotFoundHttpException(Module::t('events','ITEM_NOT_FOUND'));
        }

            $newRequirement = new Requirement();
            $newRequirement->prisoner_id = $prisoner_id;
            if ($newRequirement->load(\Yii::$app->request->post())){
                $newRequirement->save();
            }
            $newProgramPrisoner = new ProgramPrisoner();
            $newProgramPrisoner->prison_id = $prisoner->prison_id;
            $newProgramPrisoner->prisoner_id = $prisoner_id;
            $newProgramPrisoner->status_id = ProgramPrisoner::STATUS_INIT;

            if ($newProgramPrisoner->load(\Yii::$app->request->post())){
               $newProgramPrisoner->save();
            }


        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisoner->getPrisonerPrograms(),
            'pagination' => ['pageSize' => 0]
        ]);
            if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))
                    $prisonerPrograms = $prisoner->getPrisonerPrograms()->all();

            else{
                $prisonerPrograms = $prisoner->getPrisonerPrograms()->ownedBy()->all();
                $prisonerProgramsDataProvider->query->ownedBy();
            }

        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisoner->getRequirements(),
            'pagination' => ['pageSize' => 0]
        ]);

        if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING))
            $prisonerRequirements = $prisoner->getRequirements()->all();
        else{
            $prisonerRequirementsDataProvider->query->ownedBy();
            $prisonerRequirements = $prisoner->getRequirements()->ownedBy()->all();
        }


           return $this->render("index", [
               'prisoner'=>$prisoner ,
               'newRequirement'=>$newRequirement,
               'newProgramPrisoner' => $newProgramPrisoner,
               'prisonerPrograms' => $prisonerPrograms,
               'prisonerRequirements' => $prisonerRequirements,
               'requirementsList' => Requirement::getRequirementsForCombo(),
               'prisonerRequirementsDataProvider' => $prisonerRequirementsDataProvider,
               'prisonerProgramsDataProvider' => $prisonerProgramsDataProvider
           ]);
    }

    public function actionIndexPrint($prisoner_id)
    {
        if (!($prisoner = Prisoner::findOne($prisoner_id))){
            throw new NotFoundHttpException(Module::t('events','ITEM_NOT_FOUND'));
        }

        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisoner->getPrisonerPrograms(),
            'pagination' => ['pageSize' => 0]
        ]);
        //if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))
            $prisonerPrograms = $prisonerProgramsDataProvider->query->all();




        $programsGroupedByRole = [
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR => [] ,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST => [],
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST => []
        ];

        foreach ($prisonerPrograms as $program)
        {
            $roleName = $program->ownableitem->createdBy->user->role;
            if (!isset($programsGroupedByRole[$roleName]))
                continue;

            if (!isset($programsGroupedByRole[$roleName]['prog']))
                $programsGroupedByRole[$roleName]['prog'] = [];
            $programsGroupedByRole[$roleName]['prog'][] = $program;
        }

        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisoner->getRequirements(),
            'pagination' => ['pageSize' => 0]
        ]);

       // if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING))
            $prisonerRequirements = $prisonerRequirementsDataProvider->query->all();


        foreach ($prisonerRequirements as $requirement)
        {
            $roleName = $requirement->ownableitem->createdBy->user->role;
            if (!isset($programsGroupedByRole[$roleName]))
                continue;
            if (!isset($programsGroupedByRole[$roleName]['req']))
                $programsGroupedByRole[$roleName]['req'] = [];

            $programsGroupedByRole[$roleName]['req'][] = $requirement;
        }


        return $this->render("index-print", [
            'prisoner'=>$prisoner ,
            'programsGroupedByRole' => $programsGroupedByRole,
        //    'requirementsGroupedByRole' => $requirementsGroupedByRole,

        ]);
    }


}