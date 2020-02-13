<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\tasks\models;


use yii\db\ActiveQuery;

class CommitteeQuery extends ActiveQuery
{


    public function subject251()
    {
        return $this->andWhere([
            'subject_id' => Committee::SUBJECT_251,
           // 'status_id' => Committee::STATUS_FINISHED

        ]);
    }

    public function finished()
    {
        return $this->andWhere([

            'status_id' => Committee::STATUS_FINISHED

        ]);
    }




}