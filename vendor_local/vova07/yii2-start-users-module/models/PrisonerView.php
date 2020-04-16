<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;

use vova07\finances\models\Balance;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramPrisonerQuery;
use vova07\plans\models\Requirement;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;


class PrisonerView extends  Prisoner
{
    public static function tableName()
    {
        return 'vw_prisoner';
    }
    public static function primaryKey()
    {
        return ['__person_id'];
    }
    public static function find()
    {
        return new PrisonerQuery(PrisonerView::class);
    }

    /**
     * @return ProgramPrisonerQuery
     */
    public function getPrisonerPrograms()
    {
        return $this->hasMany(ProgramPrisoner::class, ['prisoner_id' => '__person_id']);
    }
}
