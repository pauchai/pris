<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\concepts\models;


use vova07\users\models\Person;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;

class ConceptParticipantQuery extends ActiveQuery
{

    public function orderByFio()
    {
        /***
         * @var $query Query
         */
        $this->joinWith(['person' => function($query){ $query->from(['person' => Person::tableName()]); }])->orderBy('person.second_name');
        return $this;
    }



    public function forActiveConcepts()
    {
        $this->andWhere([
            'in', 'concept_id', Concept::find()->active()->select('__ownableitem_id')
        ]);
        return $this;
    }

}