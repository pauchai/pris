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

class PersonQuery extends ActiveQuery
{

    public function foreigners()
    {
        return $this->andWhere(['<>', 'citizen_id', Country::findOne(['iso' => Country::ISO_MOLDOVA])->primaryKey]);

    }

    public function stateless()
    {
        return $this->andWhere([new Expression('isnull(citizen_id)')]);
    }

}