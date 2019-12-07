<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class PrisonFixture extends ActiveFixture
{
    public $modelClass = Prison::class;
    public $depends = [CompanyFixture::class];


}