<?php
namespace common\fixtures;

use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class PersonFixture extends ActiveFixture
{
    public $modelClass = Person::class;
    public $depends = [IdentFixture::class];
}