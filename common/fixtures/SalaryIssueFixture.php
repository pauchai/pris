<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\salary\models\SalaryIssue;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class SalaryIssueFixture extends ActiveFixture
{
    public $modelClass = SalaryIssue::class;
    
}