<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\prisons\models\CompanyDepartment;
use vova07\prisons\models\Department;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class CompanyDepartmentFixture extends ActiveFixture
{
    public $modelClass = CompanyDepartment::class;
    
}