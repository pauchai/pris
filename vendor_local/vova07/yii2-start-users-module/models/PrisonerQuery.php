<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\users\models;


use vova07\countries\models\Country;
use yii\db\ActiveQuery;

class PrisonerQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['status_id' => Prisoner::STATUS_ACTIVE]);
    }
    public function etapped()
    {
        return $this->andWhere(['status_id' => Prisoner::STATUS_ETAP]);
    }
    public function activeAndEtapped()
    {
        return $this->andWhere(
            ['status_id' => [Prisoner::STATUS_ACTIVE, Prisoner::STATUS_ETAP]]

        );
    }

    public function foreigners()
    {
        return $this->joinWith('person')->andWhere(['<>', 'person.citizen_id', Country::findOne(['iso' => Country::ISO_MOLDOVA])->primaryKey]);

    }

}