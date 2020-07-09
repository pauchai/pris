<?php
namespace common\fixtures;

use vova07\users\models\Ident;
use vova07\users\models\User;
use vova07\videos\models\VideoWords;
use vova07\videos\models\Word;
use yii\test\ActiveFixture;

class VideoWordFixture extends ActiveFixture
{
    public $modelClass = VideoWords::class;
    public $depends = [VideoFicture::class, WordsFixture::class];
}