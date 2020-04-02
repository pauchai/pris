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

class ProgramVisitQuery extends ActiveQuery
{
    /**
     * @return array
     */
    public function distinctDates()
    {
        return $this->distinct('date_visit')->select('date_visit')->column();

    }

    public function presented()
    {
        $this->andWhere(['program_visits.status_id'=>ProgramVisit::STATUS_PRESENT]);
        return $this;
    }
    public function doesntPresented()
    {
        $this->andWhere(['status_id'=>ProgramVisit::STATUS_DOESNT_PRESENT]);
        return $this;
    }
    public function doesntPresentedValid()
    {
        $this->andWhere(['status_id'=>ProgramVisit::STATUS_DOESNT_PRESENT_VALID]);
        return $this;
    }
    public function exceptDoesntPresentedValid()
    {
        $this->andWhere('status_id !=' .ProgramVisit::STATUS_DOESNT_PRESENT_VALID);
        return $this;
    }
    public function exceptDoesntPresented()
    {
        $this->andWhere('status_id !=' .ProgramVisit::STATUS_DOESNT_PRESENT);
        return $this;
    }
}