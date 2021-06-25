<?php
namespace vova07\plans\controllers\backend;
use vova07\base\components\BackendController;
use vova07\plans\models\backend\ProgramPrisonerSearch;
use vova07\plans\models\backend\ProgramSearch;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\plans\models\Requirement;
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

class RequirementsPrisonerController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['create', 'delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_REQUIRENMENTS_PRISONER_MANAGMENT]
            ]
        ];
        return $behaviors;
    }



    public function actionDelete($id)
    {
        if (!($programPrisoner = Requirement::findOne($id))) {
            throw new NotFoundHttpException(Module::t('programs','PROGRAM_NOT_FOUND'));
        };
        $programPrisoner->delete();
        return $this->goBack();
    }

    public function actionCreate()
    {
        $newRequirement = new Requirement();
        if ($newRequirement->load(\Yii::$app->request->post())){
            $newRequirement->save();
        }
        return $this->goBack();

    }



}