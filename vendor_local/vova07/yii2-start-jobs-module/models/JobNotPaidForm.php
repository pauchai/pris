<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\jobs\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\humanitarians\Module;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;

class JobNotPaidForm extends  JobNotPaid
{
    public $auto_fill;

    public function rules()
    {
        $rules = [
            [['prison_id', 'prisoner_id', 'type_id', 'type_id', 'month_no', 'year'], 'required'],
            [['prison_id', 'prisoner_id', 'type_id', 'type_id', 'month_no', 'year'], 'integer'],

        ];

        return $rules;


    }




}
