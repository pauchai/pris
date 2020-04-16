<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\plans\models;


use vova07\users\models\Prisoner;
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
        return $this->hasProgram()->andWhere(['status_id' => ProgramPrisoner::STATUS_ACTIVE]);
    }
    public function hasProgram()
    {
        return $this->andWhere('not isnull(program_id )');
    }

    public function forPrisonersActiveAndEtapped()
    {

        return $this->andWhere(['in ', 'prisoner_id' ,  Prisoner::find()->select('__person_id')->andWhere(
                [
                    'or',
                    ['status_id' => Prisoner::STATUS_ACTIVE],
                    ['status_id' => Prisoner::STATUS_ETAP],
                ]
        )]);
    }

    public function realized()
    {
         $this->andWhere(new Expression('program_prisoners.status_id = :status_id AND  program_prisoners.mark_id >= :mark_id',[
             ':status_id' => ProgramPrisoner::STATUS_FINISHED,
             ':mark_id' => ProgramPrisoner::MARK_SATISFACTORY,
         ]));

         return $this;
    }
    public function notRealized()
    {
        $this->andWhere(new Expression('not(program_prisoners.status_id = :status_id AND  program_prisoners.mark_id >= :mark_id)',[
            ':status_id' => ProgramPrisoner::STATUS_FINISHED,
            ':mark_id' => ProgramPrisoner::MARK_SATISFACTORY,
        ]));
        return $this;
    }


}