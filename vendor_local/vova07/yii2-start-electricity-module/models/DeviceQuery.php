<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\electricity\models;


use vova07\users\models\Prisoner;
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

    public function activePrisoners()
    {
        $subQuery = Prisoner::find()->select('__person_id')->active();
        return  $this->andWhere(
            ['prisoner_id' => $subQuery]
        );

    }
    public function active()
    {
        return $this->andWhere(['status_id' => Device::STATUS_ID_ACTIVE]);
    }

    public function deleted()
    {
        return $this->andWhere(['status_id' => Device::STATUS_ID_DELETED]);
    }

    public function inactive()
    {
        return $this->andWhere(['status_id' => Device::STATUS_ID_INACTIVE]);
    }
    public function active_or_inactive()
    {
        return $this->andWhere(
            new Expression("status_id = :active_status OR status_id = :inactive_status",
                [
                    ':active_status'  => Device::STATUS_ID_ACTIVE,
                    ':inactive_status'  => Device::STATUS_ID_INACTIVE,
                ])

        );
    }
}