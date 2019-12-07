<?php
namespace common\fixtures;

use vova07\base\models\Item;
use vova07\users\models\Ident;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class IdentFixture extends ActiveFixture
{
    public $modelClass = Ident::class;
    public $depends = [ItemFixture::class];

}