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
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;


/**
 * Class Video
 * @package vova07\video\models
 */
class VideoSearch extends Video
{

    public function search($params)
    {
        $dataProvider = new ActiveDataProvider(
            [
            'query' => Video::find()
            ]
        );
        return $dataProvider;
    }


}