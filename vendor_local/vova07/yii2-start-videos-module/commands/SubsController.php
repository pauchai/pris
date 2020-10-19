<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/28/19
 * Time: 9:38 AM
 */

namespace vova07\videos\commands;


use Done\Subtitles\Subtitles;
use vova07\videos\helpers\FFMPEGHelper;
use vova07\videos\models\Word;
use yii\console\Controller;

class SubsController extends Controller
{


    public function actionLabelToFfmpeg($labelFile, $fromFile, $toFileTemplate="./out_%03d.mp4")
    {

    $SPLITS = File($labelFile, FILE_IGNORE_NEW_LINES);
    $splitsConveted = [];
    foreach ($SPLITS as $timeStr){
        $timeArr = preg_split("#\.#", $timeStr);
        $parsed = date_parse($timeArr[0]);
        $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
        $splitsConveted[] = $seconds;
    }
    $splitsConvertedStr = join(',', $splitsConveted);
    $command = "ffmpeg -v warning -i \"$fromFile\" -c copy -map 0 -f segment -segment_times \"$splitsConvertedStr\" \"$toFileTemplate\"";
    echo $command;
    system($command);
    }

    public function actionConvert($fromFile, $toFile)
    {
        Subtitles::convert($fromFile, $toFile);
    }

    public function actionTranslationPairs()
    {
        $enVideoFile  = '/mnt/DATANTFS/PAUK/TDOWNLOADS/The.Accident.S01.WEBRip.x264-ION10/The.Accident.S01E01.WEBRip.x264-ION10.mp4';
        $ruVideoFile  = '/mnt/DATANTFS/PAUK/TDOWNLOADS/The.Accident.S01.1080p.TVShows/The.Accident.S01E01.1080p.TVShows.mkv';

        $resultFilesPath  =  '/mnt/DATANTFS/PAUK/TDOWNLOADS/The.Accident.S01.WEBRip.x264-ION10';

        $enSubFile = '/mnt/DATANTFS/PAUK/TDOWNLOADS/The.Accident.S01.WEBRip.x264-ION10/Subs/The.Accident.S01E01.WEBRip.x264-ION10/frases_en.srt';
        $ruSubFile = '/mnt/DATANTFS/PAUK/TDOWNLOADS/The.Accident.S01.WEBRip.x264-ION10/Subs/The.Accident.S01E01.WEBRip.x264-ION10/frases_ru.srt';

        $ruSubtitles = Subtitles::load($ruSubFile);
        $enSubtitles = Subtitles::load($enSubFile);

        $ruSubtitlesInternal = $ruSubtitles->getInternalFormat();
        $enSubtitlesInternal = $enSubtitles->getInternalFormat();
        $cnt = count($enSubtitlesInternal);
        for ($i = 0; $i < $cnt; $i++ ){
            $enSubInternal  = $enSubtitlesInternal[$i];
            $ruSubInternal  = $ruSubtitlesInternal[$i];
            $fileEn = FFMPEGHelper::videoTrim($enVideoFile,$enSubInternal['start'], $enSubInternal['end'] );
            $fileRu= FFMPEGHelper::videoTrim($ruVideoFile,$ruSubInternal['start'], $ruSubInternal['end'] );


            FFMPEGHelper::concatMedia([$fileRu, $fileEn, $fileEn, $fileEn], $resultFilesPath . '/' . $i . ".mp3");
            unlink($fileEn);
            unlink($fileRu);
        }






    }

}