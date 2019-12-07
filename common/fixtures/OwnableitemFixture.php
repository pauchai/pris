<?php
namespace common\fixtures;

use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\users\models\User;
use yii\test\ActiveFixture;

class OwnableitemFixture extends ActiveFixture
{
    public $modelClass = Ownableitem::class;

}