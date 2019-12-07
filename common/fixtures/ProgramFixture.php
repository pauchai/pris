<?php
namespace common\fixtures;

use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\prisons\models\Company;
use vova07\prisons\models\Department;

use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class ProgramFixture extends ActiveFixture
{
    public $modelClass = Program::class;
    
}