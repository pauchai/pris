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
use vova07\plans\models\PlanItemGroup;
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
            $newRequirement->group_id = ArrayHelper::getValue(\Yii::$app, 'user.identity.planGroup.id');


            $newProgramPrisoner = new ProgramPrisoner();
            $newProgramPrisoner->prison_id = $prisonerPlan->prisoner->prison_id;
            $newProgramPrisoner->prisoner_id = $prisoner_id;
            $newProgramPrisoner->status_id = ProgramPrisoner::STATUS_INIT;

            if ($newProgramPrisoner->load(\Yii::$app->request->post())){
               $newProgramPrisoner->save();
            }


        $prisonerProgramsQuery = $prisonerPlan->getPrisonerPrograms()->joinWith('programDict')->orderBy(['program_dicts.group_id' => SORT_DESC]);

        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>   $prisonerProgramsQuery,
            'pagination' => false
        ]);

            if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING) == false)
                $prisonerProgramsQuery->where(['program_dicts.group_id' => ArrayHelper::getValue(\Yii::$app->user->identity->getPlanGroup(),'id')]);

        $requirementsQuery = $prisonerPlan->getRequirements()->orderBy(['group_id' => SORT_DESC]);

        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' =>   $requirementsQuery,
            'pagination' => false
        ]);
        if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING) == FALSE)
                    $requirementsQuery->andWhere(['group_id' => ArrayHelper::getValue(\Yii::$app->user->identity->getPlanGroup(),'id')]);


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
               //'prisonerPrograms' => $prisonerPrograms,
               //'prisonerRequirements' => $prisonerRequirements,
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

        $prisonerProgramsQuery = $prisonerPlan->getPrisonerPrograms();
        $prisonerProgramsDataProvider  = new ActiveDataProvider([
            'query' =>  $prisonerProgramsQuery  ,
            'pagination' => false
        ]);
        //if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_PROGRAMS_PLANING))
            $prisonerPrograms = $prisonerProgramsQuery->all();




        $programsGroupedByRole = [
            PlanItemGroup::GROUP_EDUCATOR_ID=> [] ,
            PlanItemGroup::GROUP_PSYCHOLOGIST_ID => [],
            PlanItemGroup::GROUP_SOCIOLOGIST_ID => [],


           // \vova07\rbac\Module::ROLE_SUPERADMIN => []
        ];

        foreach ($prisonerPrograms as $program)
        {
            $groupId = $program->planGroup->primaryKey;

            if (!isset($programsGroupedByRole[$groupId]))
                continue;

            if (!isset($programsGroupedByRole[$groupId]['prog']))
                $programsGroupedByRole[$groupId]['prog'] = [];
            $programsGroupedByRole[$groupId]['prog'][] = $program;
        }
        $prisonerRequirementsQuery = $prisonerPlan->getRequirements();
        $prisonerRequirementsDataProvider  = new ActiveDataProvider([
            'query' => $prisonerRequirementsQuery  ,
            'pagination' => false
        ]);

       // if (\Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONER_PLAN_REQUIREMENTS_PLANING))
            $prisonerRequirements = $prisonerRequirementsQuery->all();


        foreach ($prisonerRequirements as $requirement)
        {
            $groupId = $requirement->group_id;

            if (!isset($programsGroupedByRole[$groupId]))
                continue;
            if (!isset($programsGroupedByRole[$groupId]['req']))
                $programsGroupedByRole[$groupId]['req'] = [];

            $programsGroupedByRole[$groupId]['req'][] = $requirement;
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