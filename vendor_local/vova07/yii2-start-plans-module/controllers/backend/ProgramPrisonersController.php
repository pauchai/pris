<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\plans\Module;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ProgramPrisonersController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['participants','add-participant','view','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionParticipants($program_id)
    {
        $program = Program::findOne($program_id);
//        $dates = ProgramVisit::find()->select(new Expression('DISTINCT date_visit'))->where(['program_id'=>$program_id])->asArray()->all();
        //$dates=$program->getProgramVisits()->select(new Expression('DISTINCT date_visit'))->asArray()->all();
        if (is_null($model = Program::findOne($program_id)))
        {
            throw new NotFoundHttpException(Module::t('programs',"PROGRAM_NOT_FOUND"));
        };
        $programPrisonerSearch = new ProgramPrisonerSearch();
        $dataProvider = $programPrisonerSearch->search(['program_id'=>$program_id],'');
        $dataProvider->query->active();
        $newParticipant  = new ProgramPrisoner();
        $newParticipant->program_id = $program->primaryKey;
        $newParticipant->status_id = \vova07\plans\models\ProgramPrisoner::STATUS_ACTIVE;


        if (\Yii::$app->request->isPost){
        $newParticipant->load(\Yii::$app->request->post());
        if ($newParticipant->validate()){
            $newParticipant->save(false);
        }
        }




      //  if (\Yii::$app->request->isPjax){
       //     return $this->renderPartial("participants", ['dataProvider'=>$dataProvider, 'program'=>$program, 'newParticipant'=>$newParticipant]);
       // } else {
            return $this->render("participants", ['dataProvider'=>$dataProvider, 'program'=>$program, 'newParticipant'=>$newParticipant]);
       // }

    }

    public function actionPrograms($id)
    {
        if (!($programPrisoner = ProgramPrisoner::findOne($id))) {
            throw new NotFoundHttpException(Module::t('programs','PROGRAM_NOT_FOUND'));
        };

    }

    public function actionAddParticipant()
    {
        $programPrisoner = new  ProgramPrisoner;
        //if (\Yii::$app->request->isPjax){

        $this->runAction('participants',['program_id'=>$programPrisoner->program_id]);
    }





    public function actionView($id)
    {
        if (!($model = ProgramPrisoner::findOne($id))) {
            throw new NotFoundHttpException(Module::t('programs','PROGRAM_NOT_FOUND'));
        };

        return $this->render('view', ['model'=>$model]);
    }

    public function actionUpdate($id)
    {

        if (is_null($model = ProgramPrisoner::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    //return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                    return $this->redirect(['index']);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);

    }


}