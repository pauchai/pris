<?php
namespace vova07\events\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\backend\EventParticipantSearch;
use vova07\events\models\backend\EventSearch;

use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\events\Module;

use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ParticipantsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'delete', 'create'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex($event_id)
    {
        if (!($event = Event::findOne($event_id))) {
            throw new NotFoundHttpException(Module::t('events', 'ITEM_NOT_FOUND'));
        }




        $newParticipant = new EventParticipant();
        $newParticipant->event_id = $event->primaryKey;




        \Yii::$app->user->returnUrl = Url::current();
        if (\Yii::$app->request->isPjax) {
            return $this->renderPartial("index", ['event' => $event, 'newParticipant' => $newParticipant]);
        } else {
            return $this->render("index", ['event' => $event, 'newParticipant' => $newParticipant]);
        }

    }

    public function actionDelete($event_id, $prisoner_id)
    {
        if (is_null($model = EventParticipant::findOne(['event_id' => $event_id, 'prisoner_id' => $prisoner_id]))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        if ($model->delete()) {
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default', "CANT_DELETE"));
    }

    public function actionCreate()
    {
        $newParticipant = new EventParticipant();
        if (\Yii::$app->request->isPost){
            $newParticipant->load(\Yii::$app->request->post());
            if ($newParticipant->validate()){
                $newParticipant->save(false);
            }
        }
        return $this->goBack();
    }



}