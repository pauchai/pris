<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramPlanSearch;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramQuery;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ProgramPlansController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','change-year','add-participants'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }


    public function actionIndex()
    {
        \Yii::$app->user->returnUrl  = Url::current();

        $searchModel = new ProgramPrisonerSearch(['scenario' => ProgramPrisonerSearch::SCENARIO_PLANNING]);
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->setPagination(false);
        $dataProvider->query->planned()->forPrisonersActive();


        $programQuery = Program::find()->andFilterWhere(
            [
                'programdict_id'=>$searchModel->programdict_id,
                'prison_id' => $searchModel->prison_id
            ]
        );
        if ($searchModel->date_plan){
            $programQuery->andWhere(new Expression('YEAR(date_start)=:year',[':year'=>$searchModel->date_plan]));
        }






            $newProgram = new Program();
            $newProgram->programdict_id = $searchModel->programdict_id;
            $newProgram->prison_id = $searchModel->prison_id;
            $newProgram->status_id = Program::STATUS_ACTIVE;
            if (\Yii::$app->request->post()){

                if ($newProgram->load(\Yii::$app->request->post())){
                    $newProgram->save();
                    $this->refresh();
                };

            }



        $programProvider = new ActiveDataProvider([
            'query' =>$programQuery
        ]);

            if ($this->isPrintVersion)
                $dataProvider->pagination = false;

        if (\Yii::$app->request->isPjax){
            return $this->renderPartial('index',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel,'programProvider' => $programProvider,'newProgram' => $newProgram]);
        } else {
            return $this->render('index',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel,'programProvider' => $programProvider,'newProgram' => $newProgram]);
        }



    }
    public function actionAddParticipants()
    {
        if (($selectedProgramPrisoners = \Yii::$app->request->post('ProgramPrisoner')) && ($selectedProgramId=\Yii::$app->request->post('Program'))){
            $program = Program::findOne($selectedProgramId);
            foreach ($selectedProgramPrisoners as $programPrisonerId){
                $programPrisoner = ProgramPrisoner::findOne($programPrisonerId);
                $programPrisoner->program_id = $program->primaryKey;
                $programPrisoner->status_id = ProgramPrisoner::STATUS_ACTIVE;
                $programPrisoner->save();
            }
        }
        return $this->goBack();

/*        return \Yii::createObject([
            'class' => \yii\web\Response::class,
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'code' => 100,
],
]);
   */
    }

    public function actionChangeYear($id)
    {
        $key = $id;// compact(['prisoner_id','programdict_id']);
        $programPrisoner = ProgramPrisoner::findOne($key);

        if ($programPrisoner->load(\Yii::$app->request->post(),"")){
            $programPrisoner->status_id = ProgramPrisoner::STATUS_PLANED;
            $programPrisoner->save();
        }

        return $programPrisoner->date_plan;
    }


}