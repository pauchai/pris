<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/13/19
 * Time: 9:56 AM
 */

namespace vova07\videos\models;
use Done\Subtitles\Subtitles;
use vova07\videos\Module;
use yii\db\ActiveRecord;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;


/**
 * Class Video
 * @package vova07\video\models
 */
class Video extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_METADATA_FETCHED = 1;
    const STATUS_ACTIVE = 2;

    const SCENARIO_FETCH_METADATA = "fetch_metadata";
    const SCENARIO_DOWNLOAD_VIDEO = "download_video";
    /**
     * @var $video_url
     * @var $sub_url
     * @var $title
     * @var $type
     * @var $thumbnail_url
     */

    private $_metaData ;

    public function init()
    {
        parent::init();
        /*$this->on(self::EVENT_BEFORE_VALIDATE, function($event){
           $model = $event->sender;
           if ($model->video_url){
               if (is_null($model->title)){
                   $model->title = pathinfo($model->video_url, PATHINFO_FILENAME);
               }
           }
        });*/
    }
    /**
     * @return string
     */
    static function tableName()
    {
        return "videos";
    }


    public function rules()
    {
        return [
            [['video_url'], 'required'],
            [['title', 'sub_url', 'type', 'thumbnail_url'], 'safe']

        ];
    }

    public function getFullSrcUrl()
    {
        return Module::getInstance()->videosBaseUrl . "/" . $this->video_url;
    }

    public function getFullSubTitleTrackUrl($index=0)
    {
        return Module::getInstance()->subTitlesBaseUrl . "/" . $this->metadata['subtitles'][$index]['filename'];
    }

    public function getSubTitles()
    {


        $internalFormat = $this->getSubTitlesInternal();
        $content = "";
        foreach ($internalFormat as $subItem) {
            //$content .=  $subItem['start'] . ':' . $subItem['end'] . "<br/>";
            $lineId = $subItem['start'] . $subItem['end'];
            $lineId = preg_replace("/\./", "", $lineId);
            $lines = join("\n", $subItem['lines']);
            $posArr = preg_split("/\./", $subItem['start']);
            //$timePosition = $posArr[0] * 1000 + $posArr[1];
            //$timePosition = $posArr[0];
            $timePosition = $subItem['start'];
            $content .= "<a class='cue' id='$lineId' href='#" . $timePosition . "' >$lines</a>" . "\n";
        }

        return nl2br(strip_tags($content, '<a></a>'));
    }

    public function getSubTitlesInternal()
    {

        $subsObj = Subtitles::load(\Yii::getAlias(Module::getInstance()->subTitlesBasePath . "/" . $this->sub_url));
        return $subsObj->getInternalFormat();

    }

    public static function find()
    {
        return new VideoQuery(get_called_class());
    }

    public function getVideoWords()
    {
        return $this->hasMany(VideoWords::class, ['video_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWords()
    {
        return $this->hasMany(Word::class, ['id' => 'word_id'])->via('videoWords');
        //  return $this->hasMany(Word::class,['id' => "word_id"])->viaTable("video_word",['order_id' => 'id']);
    }

    public static function getVideosPath()
    {
        return \Yii::getAlias(Module::getInstance()->subTitlesBasePath);
    }



    public function beforeSave($insert)
    {
        if ($this->video_url){
            $fileName = pathinfo($this->video_url, PATHINFO_FILENAME);
            if (!($this->title)){
                $this->title = $fileName;
            };

            if (!($this->sub_url)){

                $filesArr = glob(\Yii::getAlias(Module::getInstance()->subTitlesBasePath . "/" .$fileName . '*.vtt'));
                if (count($filesArr)){
                    $this->sub_url = pathinfo($filesArr[0],PATHINFO_FILENAME) . "." . pathinfo($filesArr[0],PATHINFO_EXTENSION);

                }
            };
            if (!$this->thumbnail_url){
                $filesArr = glob(\Yii::getAlias(Module::getInstance()->subTitlesBasePath . "/" .$fileName . '*.jpg'));
                if (count($filesArr)){
                    $this->thumbnail_url = pathinfo($filesArr[0],PATHINFO_FILENAME) . "." . pathinfo($filesArr[0],PATHINFO_EXTENSION);

                }
            }
            if (!$this->type){
                $this->type="video/" . pathinfo($this->video_url, PATHINFO_EXTENSION);
            }
        }


        return true;
    }



    public function getMetaData()
    {

    }




}