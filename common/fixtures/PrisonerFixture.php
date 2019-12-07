<?php
namespace common\fixtures;

use vova07\prisons\models\Prisoner;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class PrisonerFixture extends ActiveFixture
{
    public $modelClass = \vova07\users\models\Prisoner::class;
    public $depends = [PersonFixture::class];
}