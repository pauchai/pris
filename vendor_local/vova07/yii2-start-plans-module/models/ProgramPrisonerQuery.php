<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\plans\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

class ProgramPrisonerQuery extends ActiveQuery
{

    public function ownedBy()
    {
        return $this->joinWith('ownableitem')->andWhere('ownableitem.created_by='. \Yii::$app->user->id);
    }

    public function planned()
    {
        return $this->andWhere(['status_id' => ProgramPrisoner::STATUS_PLANED])->andWhere('program_id is null');
    }
    public function active()
    {
        return $this->andWhere(['status_id' => ProgramPrisoner::STATUS_ACTIVE])->andWhere('not(program_id  is null)');
    }

}