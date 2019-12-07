<?php
namespace vova07\events\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\backend\EventSearch;

use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\events\Module;

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
                'actions' => ['index'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionIndex($event_id)
    {
        if (!($event = Event::findOne($event_id))){
            throw new NotFoundHttpException(Module::t('events','ITEM_NOT_FOUND'));
        }

        $newParticipant  = new EventParticipant();
        $newParticipant->event_id = $event->primaryKey;



        if (\Yii::$app->request->isPost){
            $newParticipant->load(\Yii::$app->request->post());
            if ($newParticipant->validate()){
                $newParticipant->save(false);
            }
        }


        if (\Yii::$app->request->isPjax){
            return $this->renderPartial("index", ['event'=>$event,'newParticipant'=>$newParticipant ]);
        } else {
            return $this->render("index", ['event'=>$event,'newParticipant'=>$newParticipant ]);
        }

    }



}