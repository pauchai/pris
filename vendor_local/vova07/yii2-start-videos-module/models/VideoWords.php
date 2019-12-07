<?php

namespace vova07\videos\models;

use Yii;


class VideoWords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_word';
    }


}
