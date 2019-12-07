<?php
namespace common\helpers;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/4/19
 * Time: 11:11 AM
 */

class User
{
    public  function createPrison($faker)
    {
        $prison = new Prison();
        $company = new Company();
        $company->title = $faker->company;

        $prison->company = $company;
        $prison->save();
        return $prison;
    }
}