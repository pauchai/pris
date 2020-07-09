<?php
namespace common\fixtures;

use vova07\users\models\Ident;
use vova07\users\models\User;
use vova07\videos\models\Video;
use yii\test\ActiveFixture;

class VideoFicture extends ActiveFixture
{
    public $modelClass = Video::class;

}