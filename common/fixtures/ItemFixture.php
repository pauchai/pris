<?php
namespace common\fixtures;

use vova07\base\models\Item;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class ItemFixture extends ActiveFixture
{
    public $modelClass = Item::class;

}