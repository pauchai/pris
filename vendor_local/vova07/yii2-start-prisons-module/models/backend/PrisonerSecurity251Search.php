<?php
namespace vova07\prisons\models\backend;
use vova07\base\components\DateJuiBehavior;
use vova07\prisons\models\Prison;
use vova07\prisons\models\PrisonerSecurity;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerSecurity251Search extends \vova07\prisons\models\backend\PrisonerSecuritySearch
{

    public $availableTypes = [PrisonerSecurity::TYPE_250, PrisonerSecurity::TYPE_251];



}