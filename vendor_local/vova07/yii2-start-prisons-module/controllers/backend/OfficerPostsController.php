<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\prisons\controllers\backend;


use vova07\base\components\BackendController;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPostView;
use vova07\prisons\models\Post;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\backend\OfficerPostSearch;
use vova07\users\models\Officer;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class OfficerPostsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'officer-posts', 'create','view','delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }


    public function actionIndex()
    {

        $searchModel = new OfficerPostSearch();
        $searchModel->company_id = \Yii::$app->base->company->primaryKey;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $newModel = new OfficerPost();
        $newModel->attributes = $searchModel->getAttributes(['officer_id', 'company_id','division_id','postdict_id']);
        $newModel->full_time = true;
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel, 'newModel' => $newModel]);
    }
    public function actionOfficerPosts()
    {
        $dataProvider = new ActiveDataProvider(
        [
          'query' => OfficerPostView::find()->orderBy(['second_name' => SORT_ASC, 'first_name' => SORT_ASC, 'patronymic' => SORT_ASC])
        ]
        );
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("officer_posts", ['dataProvider'=>$dataProvider]);


    }

    public function actionCreate()
    {

        $model = new OfficerPost();

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->goBack();
            }
        }


        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionUpdate($officer_id,$company_id,$division_id, $postdict_id)
    {
        if (is_null($model = OfficerPost::findOne(compact('officer_id','company_id', 'division_id', 'postdict_id'))))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->goBack();
        };
        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionDelete($officer_id,$company_id,$division_id, $postdict_id)
    {
        if (is_null($model = OfficerPost::findOne(compact('officer_id','company_id', 'division_id', 'postdict_id'))))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->delete()){

            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }




}