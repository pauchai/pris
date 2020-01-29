<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\electricity\models;


use yii\db\ActiveQuery;

class DeviceQuery extends ActiveQuery
{

/*
    public function active()
    {
        return $this->andWhere(['status_id' => Event::STATUS_ACTIVE]);
    }
*/

    public function byPrisoner($prisonerId)
    {
        return $this->andWhere([
            'prisoner_id' => $prisonerId,
        ]);
    }
}