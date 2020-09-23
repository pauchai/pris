<?php
namespace common\fixtures;

use vova07\base\models\Item;
use vova07\salary\models\Salary;
use vova07\salary\models\SalaryWithHold;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class SalaryWithHoldFixture extends ActiveFixture
{
    public $modelClass = SalaryWithHold::class;
    public $depends = [SalaryFixture::class];

}