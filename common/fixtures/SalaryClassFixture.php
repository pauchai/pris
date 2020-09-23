<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Division;
use vova07\prisons\models\Post;
use vova07\salary\models\SalaryClass;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class SalaryClassFixture extends ActiveFixture
{
    public $modelClass = SalaryClass::class;
    
}