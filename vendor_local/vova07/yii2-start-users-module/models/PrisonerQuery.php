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
use yii\db\Expression;

class PrisonerQuery extends ActiveQuery
{

    public function active()
    {
        return $this->andWhere(['prisoner.status_id' => Prisoner::STATUS_ACTIVE]);
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
    public function locals()
    {
        return $this->joinWith('person')
            ->andWhere(['person.citizen_id' => Country::findOne(['iso' => Country::ISO_MOLDOVA])->primaryKey]);
    }
    public function foreigners()
    {
        return $this->joinWith('person')
            ->andWhere(['<>', 'person.citizen_id', Country::findOne(['iso' => Country::ISO_MOLDOVA])->primaryKey])
            ->andWhere(['>', 'person.citizen_id', 0]);

    }

    public function stateless()
    {
        return $this->joinWith('person')->andWhere(new Expression('isnull(person.citizen_id)'));

    }

}