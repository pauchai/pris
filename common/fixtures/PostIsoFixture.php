<?php
namespace common\fixtures;

use vova07\prisons\models\Company;
use vova07\prisons\models\Department;
use vova07\prisons\models\Division;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostIso;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class PostIsoFixture extends ActiveFixture
{
    public $modelClass = PostIso::class;
    
}