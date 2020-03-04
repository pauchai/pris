<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\concepts\models;


use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptVisit;
use yii\db\ActiveQuery;
use yii\db\Expression;

class ConceptVisitQuery extends ActiveQuery
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
        $this->andWhere(['status_id'=>ConceptVisit::STATUS_PRESENT]);
        return $this;
    }
    public function absented()
    {
        $this->andWhere(['status_id'=>ConceptVisit::STATUS_ABSENT]);
        return $this;
    }


}