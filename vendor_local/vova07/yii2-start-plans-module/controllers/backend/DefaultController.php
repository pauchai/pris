<?php
namespace vova07\plans\controllers\backend;
use http\Url;
use vova07\base\components\BackendController;
use vova07\comments\components\CommentCreateAction;
use vova07\plans\models\backend\EventSearch;
use vova07\plans\models\backend\ProgramDictSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Event;
use vova07\plans\models\EventParticipant;
use vova07\plans\models\PrisonerPlan;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\Requirement;
use vova07\plans\models\RequirementsQuery;
use vova07\plans\Module;
use vova07\users\models\backend\PrisonerViewSearch;
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
                'actions' => ['index','index-print', 'create', 'update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_VIEW],
            ],
            [
            'allow' => true,
            'actions' => ['createComment'],
            'roles' => [\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_COMMENT_CREATE]
            ]
        ];
        return $behaviors;
    }
    public function actions()
    {
        return [
            'createComment' => [
                'class' => CommentCreateAction::class,

            ]
        ];
    }
    public function actionIndex($prisoner_id)
    {
        \Yii::$app->user->setReturnUrl(\yii\helpers\Url::current());

        if (!($prisonerPlan = PrisonerPlan::findOne($prisoner_id))){
            //throw new NotFoundHttpException(Module::t('events','ITEM_NOT_FOUND'));
           return $this->redirect(['create','prisoner_id' => $prisoner_id]);
        }

            $newRequirement = new Requirement();
            $newRequirement->prisoner_id = $prisoner_id;

            $newProgramPrisoner = new ProgramPrisoner();
            $newProgramPrisoner->prison_id = $prisonerPlan->prisoner->prison_id;
            $newProgramPrisoner->prisoner_id = $prisoner_id;
            $newProgramPrisoner->status_id = ProgramPrisoner::STATUS_INIT;

            if ($newProgramPrisoner->load(\Yii::$app->request->post())){
               $newProgramPrisoner->save();
            }


        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisonerPlan->getPrisonerPrograms(),
            'pagination' => ['pageSize' => 0]
        ]);
            if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))
                    $prisonerPrograms = $prisonerPlan->getPrisonerPrograms()->all();

            else{
                $prisonerPrograms = $prisonerPlan->getPrisonerPrograms()->ownedBy()->all();
                $prisonerProgramsDataProvider->query->ownedBy();
            }

        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisonerPlan->getRequirements(),
            'pagination' => ['pageSize' => 0]
        ]);

        if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING))
            $prisonerRequirements = $prisonerPlan->getRequirements()->all();
        else{
            $prisonerRequirementsDataProvider->query->ownedBy();
            $prisonerRequirements = $prisonerPlan->getRequirements()->ownedBy()->all();
        }


        $prisonerSearch = new PrisonerViewSearch();
        $dataProvider = $prisonerSearch->searchFromSession();
        $dataProvider->pagination = false;
        // $searchModels = $dataProvider->getModels();
        $searchKeys = $dataProvider->getKeys();
        $arrayIndex = array_search($prisoner_id,$searchKeys);
        $prevId = isset($searchKeys[$arrayIndex-1])?$searchKeys[$arrayIndex-1]:null;
        $nextId = isset($searchKeys[$arrayIndex+1])?$searchKeys[$arrayIndex+1]:null;

           return $this->render("index", [
               'prisonerPlan'=>$prisonerPlan ,
               'newRequirement'=>$newRequirement,
               'newProgramPrisoner' => $newProgramPrisoner,
               'prisonerPrograms' => $prisonerPrograms,
               'prisonerRequirements' => $prisonerRequirements,
               'requirementsList' => Requirement::getRequirementsForCombo(),
               'prisonerRequirementsDataProvider' => $prisonerRequirementsDataProvider,
               'prisonerProgramsDataProvider' => $prisonerProgramsDataProvider,
               'prevPrisonerId' => $prevId,
               'nextPrisonerId' => $nextId,

           ]);
    }

    public function actionIndexPrint($prisoner_id)
    {
        if (!($prisonerPlan = PrisonerPlan::findOne($prisoner_id))){
            throw new NotFoundHttpException(Module::t('events','ITEM_NOT_FOUND'));
        }

        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisonerPlan->getPrisonerPrograms(),
            'pagination' => ['pageSize' => 0]
        ]);
        //if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))
            $prisonerPrograms = $prisonerProgramsDataProvider->query->all();




        $programsGroupedByRole = [
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR => [] ,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST => [],
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST => [],

           // \vova07\rbac\Module::ROLE_SUPERADMIN => []
        ];

        foreach ($prisonerPrograms as $program)
        {
            $roleName = $program->ownableitem->createdBy->user->role;
            if ($roleName === \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT)
                    $roleName = \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR;

            if (!isset($programsGroupedByRole[$roleName]))
                continue;

            if (!isset($programsGroupedByRole[$roleName]['prog']))
                $programsGroupedByRole[$roleName]['prog'] = [];
            $programsGroupedByRole[$roleName]['prog'][] = $program;
        }

        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisonerPlan->getRequirements(),
            'pagination' => ['pageSize' => 0]
        ]);

       // if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING))
            $prisonerRequirements = $prisonerRequirementsDataProvider->query->all();


        foreach ($prisonerRequirements as $requirement)
        {
            $roleName = $requirement->ownableitem->createdBy->user->role;
            if ($roleName === \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT)
                $roleName = \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR;
            if (!isset($programsGroupedByRole[$roleName]))
                continue;
            if (!isset($programsGroupedByRole[$roleName]['req']))
                $programsGroupedByRole[$roleName]['req'] = [];

            $programsGroupedByRole[$roleName]['req'][] = $requirement;
        }


        return $this->render("index-print", [
            'prisonerPlan'=>$prisonerPlan ,
            'programsGroupedByRole' => $programsGroupedByRole,
        //    'requirementsGroupedByRole' => $requirementsGroupedByRole,

        ]);
    }

    public function actionCreate($prisoner_id)
    {
        $model = new PrisonerPlan([
            '__prisoner_id' => $prisoner_id,
            'status_id' => PrisonerPlan::STATUS_ACTIVE,
        ]);
        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }

        return $this->render('create', ['model' => $model]);
    }
    public function actionUpdate($prisoner_id)
    {
        $model =  PrisonerPlan::findOne($prisoner_id);
        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }

        return $this->render('update', ['model' => $model]);
    }


}