<?php
namespace common\fixtures;

use vova07\users\models\Ident;
use vova07\users\models\User;
use vova07\videos\models\Word;
use yii\test\ActiveFixture;

class WordsFixture extends ActiveFixture
{
    public $modelClass = Word::class;
}