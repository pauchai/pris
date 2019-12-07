<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/18/19
 * Time: 5:52 PM
 */

namespace vova07\videos\models;


use vova07\videos\Module;
use yii\base\Model;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use YoutubeDl\YoutubeDl;

/**
 * Class Import
 *
 * @package vova07\videos\models
 */
class Import extends Model
{
    /**
     * @var $url
     */
    public $url;
    public $format;
    public $sub_format;
    public $thumb;


    private $_metaData ;
    /**
     * @var \YoutubeDl\Entity\Video
     */
    private $_ytVideo;

    private $_isVideoDownloaded ;


    public function rules()
    {
        return [
            [['url'],'required'],
            [['format', 'sub_format'], 'safe']
        ];
    }


    public function loadVideoInfo()
    {
        $config = [
            'continue' => true, // force resume of partially downloaded files. By default, youtube-dl will resume downloads if possible.
            //'format' => 'worstvideo[ext=webm]+worstaudio[ext=webm]',
            //'format' => 'worstvideo+worstaudio',
            'write-sub' => true,
            'write-auto-sub' => true,
            'write-thumbnail' => true,
//            'sub-lang' => 'en',
 //           'sub-format' => 'vtt',
            //'simulate' => true,
            'skip-download' => true,

        ];
        if ($this->format){
            if (isset($config['skip-download']))
                    unset($config['skip-download']);

            $config['format'] = $this->format;

        }
        if ($this->sub_format){
            list($subLang,$subFormat)=preg_split('#_#', $this->sub_format);
            $config['sub-lang'] = $subLang;
            $config['sub-format'] = $subFormat;
        }


// For more options go to https://github.com/rg3/youtube-dl#user-content-options
        //$this->_metaData = !\Yii::$app->cache->get($this->url);
        if (!$this->_metaData ){
            $dl = new YoutubeDl($config);
            $dl->setDownloadPath(\Yii::getAlias(Module::getInstance()->videosBasePath));
            $this->_ytVideo = $dl->download($this->url);
            //\Yii::$app->cache->set($this->url, $this->_ytVideo->metaData);

        } else {
            $this->_ytVideo = new \YoutubeDl\Entity\Video($this->_metaData);

        }
        $this->_isVideoDownloaded = !(isset($config['skip-download']) && $config['skip-download'] === true);

        return true;



           // $this->videoModel->title = $video->getTitle();
           // $this->videoModel->video_url = $video->getFilename();
           // $this->videoModel->sub_url = pathinfo($this->videoModel->video_url, PATHINFO_FILENAME). '.en.vtt';
           // $this->videoModel->type = "video/webm";
           // $this->videoModel->thumbnail_url =pathinfo($this->videoModel->video_url, PATHINFO_FILENAME). '.jpg';

//            $this->thumbNail =

            // $video->getFile(); // \SplFileInfo instance of downloaded file

    }

    public function getYtVideo()
    {
        if ($this->_ytVideo === null){
            throw new NotFoundException('video not loaded');
        }
        return $this->_ytVideo;
    }

    public function ytVideoIsLoaded()
    {
        return  ($this->_ytVideo instanceof \YoutubeDl\Entity\Video) ;
    }

    public function isVideoDownloaded()
    {
        return $this->_isVideoDownloaded;
    }

    public function getSubtitlesAvailableForCombo()
    {
        if ($this->_ytVideo === null){
            throw new NotFoundException('video not loaded');
        }

        $result = [];
        $subsByLang = $this->_ytVideo->getSubtitles();
        foreach ($subsByLang as $lang=>$subs){
            foreach ($subs as $sub){
                $key = $lang . '_' . $sub['ext'];
                $result[$key] = $lang . ' ' . $sub['ext'];
            }
        }
        return $result;
    }

    public function getFormatsAbailableForCombo()
    {
        if ($this->_ytVideo === null){
            throw new NotFoundException('video not loaded');
        }
        $result = [];
        $availableFormats = $this->_ytVideo->getFormats();
        foreach ($availableFormats as $format){
            $result[$format->getFormatId()] = $format->getFormatNote() . ' ' . $format->getFormat() . ' ' . $format->getFilesize() . 'byte '  . 'vCodec=' . $format->getVcodec() . ' ' . 'aCodec=' . $format->getAcodec() . ' ' . $format->getExt();
        }

        return $result;

    }
}