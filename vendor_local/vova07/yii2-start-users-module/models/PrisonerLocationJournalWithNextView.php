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
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\backend\PrisonerLocationJournalWithNextViewSearch;
use vova07\users\models\Prisoner;
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class PrisonerLocationJournalWithNextView extends  PrisonerLocationJournal
{



    public static function tableName()
    {
        return 'vw_location_journal_with_next';
    }

    public function getNext()
    {
        return $this->hasOne(PrisonerLocationJournal::class,['id' => 'next_id']);
    }
    public static function primaryKey()
    {
        return ['id'];
    }

    public static function find()
    {
        return new PrisonerLocationJournalWithNextViewQuery(get_called_class());
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels()
            ,
            [

                'at' => Module::t('labels', 'PRISONER_JOURNAL_AT_LABEL'),
                'next.at' => Module::t('labels', 'PRISONER_JOURNAL_NEXT_AT_LABEL'),

            ]);
    }
}
