<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/28/19
 * Time: 9:38 AM
 */

namespace vova07\videos\commands;


use Done\Subtitles\Subtitles;
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

}