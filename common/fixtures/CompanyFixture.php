<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class CompanyFixture extends ActiveFixture
{
    public $modelClass = Company::class;
    
}