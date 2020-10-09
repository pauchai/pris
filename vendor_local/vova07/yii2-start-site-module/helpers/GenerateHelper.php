<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/9/19
 * Time: 8:53 AM
 */

namespace vova07\site\helpers;


use vova07\countries\models\Country;
use vova07\finances\models\Balance;
use vova07\finances\models\BalanceCategory;
use vova07\jobs\models\JobNotPaidType;
use vova07\jobs\models\JobPaid;
use vova07\jobs\models\JobPaidType;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\prisons\models\Company;
use vova07\documents\models\Document;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\prisons\Module;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use vova07\users\models\User;
use Yii;
use yii\db\Expression;
use yii\db\IntegrityException;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FormatConverter;
use vova07\base\components\CsvFile;


class GenerateHelper
{

    public static function importUsers()
    {

    }

    public static function createUser($attributes,$ident)
    {

        $user = new \vova07\users\models\backend\User();
        $user->scenario = \vova07\users\models\backend\User::SCENARIO_BACKEND_CREATE;
        $user->ident = $ident;
        $user->attributes = $attributes;
        $user->status_id = User::STATUS_ACTIVE;
        $user->save();

        return $user;
    }
    public static function createIdent()
    {
        $ident = new Ident;
        $ident->save();
        return $ident;

    }
}
