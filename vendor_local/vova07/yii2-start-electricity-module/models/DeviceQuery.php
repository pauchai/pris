<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\electricity\models;


use yii\db\ActiveQuery;
use yii\db\Expression;

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
    public function hasPrisoner()
    {
        return $this->andWhere(
          new Expression('not isnull(prisoner_id)')
        );
    }
    public function withoutPrisoner()
    {
        return $this->andWhere(
            new Expression('isnull(prisoner_id)')
        );
    }
}