<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\jobs\models;



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
use vova07\plans\models\Requirement;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;


class JobsGeneralListView extends  ActiveRecord
{
    public static function tableName()
    {
        return 'vw_jobs_general';
    }
    public static function primaryKey()
    {
        return ['prisoner_id'];
    }
    public static function find()
    {
        return new JobsGeneralListViewQuery(get_called_class());
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ident_id'=>'prisoner_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id'=>'prisoner_id']);
    }

    public static function getActiveYears()
    {
        return ArrayHelper::map(self::find()->select(['year'])->distinct()->asArray()->all(),'year','year');

    }
}
