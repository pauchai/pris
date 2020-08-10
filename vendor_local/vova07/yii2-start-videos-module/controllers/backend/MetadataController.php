<?php
namespace vova07\videos\controllers\backend;



use vova07\base\components\BackendController;
use vova07\videos\models\Import;
use vova07\videos\models\metadata\SubTitle;
use vova07\videos\models\Video;
use vova07\videos\models\VideoSearch;
use vova07\videos\models\VideoWordForm;
use vova07\videos\models\Word;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;
use yii2tech\csvgrid\CsvGrid;
use YoutubeDl\Exception\NotFoundException;

class MetadataController extends BackendController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::className()
            ],
        ];
    }



    public function actionMetadata($id)
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $video = Video::findOne($id);
        $metaData = $video->metadata;
        $subsDataProvider = new ArrayDataProvider([
            'allModels' => $video->metadata['subtitles'],
        ]);
        return $this->render('metadata', ['model' => $video, 'subsDataProvider' => $subsDataProvider]);

    }

    public function actionSubtitleCreate($video_id)
    {
        $video = Video::findOne($video_id);
        $metaData = $video->metadata;
        $subsDataProvider = new ArrayDataProvider([
            'allModels' => $video->metadata['subtitles'],
        ]);

        $model = new SubTitle;
        if (\Yii::$app->request->isPost){
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file){
                $model->upload();
            } else {
                $model->load(\Yii::$app->request->post());
            }
            if ($model->validate()){
                ArrayHelper::setValue($video,'metadata.subtitles')
                $video->metadata['subtitles'][] = $model->getAttributes(['name', 'type', 'filename']);
                $video->save();
                $this->goBack();
            }



        }
        return $this->render('subtitle_create', ['video' => $video, 'model' => $model]);
    }


}