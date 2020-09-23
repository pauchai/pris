<?php
namespace common\fixtures;

use vova07\base\models\Item;
use vova07\salary\models\Salary;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class SalaryFixture extends ActiveFixture
{
    public $modelClass = Salary::class;
    public $depends = [SalaryIssueFixture::class];

}