<?php
namespace common\fixtures;

use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
    public $depends = [IdentFixture::class];
}