<?php
namespace vova07\videos\controllers\backend;



use Done\Subtitles\Subtitles;
use kartik\form\ActiveForm;
use vova07\base\components\BackendController;
use vova07\videos\models\Import;
use vova07\videos\models\Video;
use vova07\videos\models\VideoSearch;
use vova07\videos\models\VideoWordForm;
use vova07\videos\models\Word;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii2tech\csvgrid\CsvGrid;
use YoutubeDl\Exception\NotFoundException;

class DefaultController extends BackendController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::className()
            ],
        ];
    }
   
    public function actionIndex()
    {
        \Yii::$app->user->setReturnUrl(Url::current());
        $searchModel = new VideoSearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search(\Yii::$app->request->queryParams),

        ]);
    }

    public function actionCreate()
    {
        $model = new Video();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
              //  return $this->redirect(Url::toRoute("index"));
            }
        }
        return $this->render("create", [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Video::findOne($id);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(Url::toRoute("index"));
            }
        }
        return $this->render("update", [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        \Yii::$app->user->setReturnUrl(Url::current());

        $model = Video::findOne($id);
        $newWord = new VideoWordForm([
            'video_id' => $id,
        ]);

        return $this->render('view', ['model' => $model, 'newWord' => $newWord]);

    }

    public function actionImportYoutube()
    {
        $model = new Import();
        $ytVideo = null;

        if ($model->load(\Yii::$app->request->post())){
               $model->loadVideoInfo();
               if ($model->isVideoDownloaded()){
                       $videoModel = new Video();
                       $videoModel->title = $model->getYtVideo()->getTitle();
                       $videoModel->video_url = $model->getYtVideo()->getFilename();
                       if ($model->format){
                           list($subLang,$subFormat)=preg_split('#_#', $model->sub_format);
                           $videoModel->sub_url = pathinfo($videoModel->video_url, PATHINFO_FILENAME). '.' . $subLang . '.' . $subFormat ;
                       }

                   // $this->videoModel->sub_url = pathinfo($this->videoModel->video_url, PATHINFO_FILENAME). '.en.vtt';
                   $videoModel->type = "video/" . pathinfo($videoModel->video_url, PATHINFO_EXTENSION);
                   $videoModel->thumbnail_url =pathinfo($videoModel->video_url, PATHINFO_FILENAME). '.jpg';
                    return $this->render('create', ['action' => ['create'],'model'=>$videoModel]);


               }


        }

        return $this->render("import", [
            'model' => $model, 'ytVideo' => $ytVideo
        ]);

    }

    public function actionGetProgress()
    {
        return \Yii::createObject([
            'class' => Response::class,
            'format' => \yii\web\Response::FORMAT_JSON,
            'data' => [
                'percentage' => \Yii::$app->session['percentage']
            ]
        ]);
    }

    public function actionDelete($id)
    {
        $model = Video::findOne($id);

        if ($model->delete()){
            return $this->redirect(['index']);
        } ;
        throw new NotFoundException("не могу удалить");
    }



    public function actionExportAnki($id)
    {
        $model = Video::findOne($id);
        $words = $model->getWords()->select('title')->column();
        $subs = $model->getSubTitlesInternal();
        $exportArray = [];
        foreach ($subs as $sub){
            $lines = join(',',$sub['lines']);
            $matches = [];
            if (preg_match('#(' . join('|',$words). ')#', $lines, $matches)){
                $matchedWord = $matches[0];
                $startTime = $sub['start'];

                $endTime = $sub['end'];
                $duration = $sub['end'] - $sub['start'];
                $inputFile = Video::getVideosPath() . "/" . $model->video_url;
                $outputMp3 = Video::getVideosPath() .'/shots/' .pathinfo($model->video_url, PATHINFO_FILENAME). $startTime . '-' . $endTime . '.mp3';
                $outputJpg = Video::getVideosPath() .'/shots/' .pathinfo($model->video_url, PATHINFO_FILENAME). $startTime . '.jpg';
                $cutMp3FromTime = strtr('ffmpeg -ss {{%start_time}} -t {{%duration}} -i {{%input}} {{%output}}',
                    ['{{%start_time}}' => $startTime,'{{%duration}}' => $duration, '{{%input}}'=>$inputFile, '{{%output}}'=>$outputMp3]
                );
                $cutJpegFromTime = strtr('ffmpeg -ss {{%start_time}} -i {{%input}} -vframes 1 -q:v 2  {{%output}}',
                    ['{{%start_time}}' => $startTime,'{{%duration}}' => $duration, '{{%input}}'=>$inputFile, '{{%output}}'=>$outputJpg]);
                if (!file_exists($outputJpg)){
                    system($cutJpegFromTime);
                }
                if (!file_exists($outputMp3)){
                    system($cutMp3FromTime);
                }
                $exportArray[] = [
                    'sub' => $lines,
                    'back' => '',
                    'short' => $outputJpg,
                    'mp3' => '[sound:' . pathinfo($model->video_url, PATHINFO_FILENAME). $startTime . '-' . $endTime . '.mp3'. ']',
                    'word' => $matchedWord,
                    'word_translation' => Word::findOne(['title' => $matchedWord])->translation,
                ];





            }


        }

        $exporter = new CsvGrid([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $exportArray
            ]),
            'columns' => [
                [
                    'attribute' => 'sub',
                ],
                [
                    'attribute' => 'back',
                ],
                [
                    'attribute' => 'short',
                ],
                [
                    'attribute' => 'mp3',
                ],
                [
                    'attribute' => 'word',
                ],

                [
                    'attribute' => 'word_translation',
                ],
            ],
        ]);
        //$exporter->export()->saveAs('/path/to/file.csv');
        return $exporter->export()->send();

    }

    public function actionVideoWords($video_id)
    {
        $video = Video::findOne($video_id);
        $dataProvider = new ActiveDataProvider([
            'query' => $video->getWords()
        ]);

        $newWord = new VideoWordForm([
            'video_id' => $video->primaryKey,
        ]);
        $wordForm = new VideoWordForm();
        if ($wordForm->load(\Yii::$app->request->post()) && $wordForm->validate()){
            $wordForm->saveWord();
        }

        return $this->renderAjax("video_words", ['video' => $video, 'newWord' => $newWord]);


    }
/*    public function actionCreateWord()
    {
        $wordForm = new VideoWordForm();
        if ($wordForm->load(\Yii::$app->request->post()) && $wordForm->validate()){
            $wordForm->saveWord();
        }
        $this->redirect(['video-words', 'video_id' => $wordForm->video_id]);
    }*/



}