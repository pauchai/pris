<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models;



use vova07\documents\models\Document;
use vova07\prisons\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\ActiveRecord;
use vova07\socio\models\RelationMaritalViewQuery;
class RelationMaritalView   extends  ActiveRecord
{
    public static function tableName()
    {
        return 'vw_relation_marital';
    }

    public static function find()
    {
        return new RelationMaritalViewQuery(get_called_class());

    }


    public static function primaryKey()
    {
        return ['__person_id', 'ref_person_id'];
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ownableitem_id' => '__person_id']);
    }

    public function getRefPerson()
    {
        return $this->hasOne(Person::class, ['__ownableitem_id' => 'ref_person_id']);
    }

    public function getMaritalStatus()
    {
        return $this->hasOne(MaritalStatus::class, ['id' => 'marital_status_id']);
    }

    public function getRelationType()
    {
        return $this->hasOne(RelationType::class, ['id' => 'relation_type_id']);
    }

    public function getPersonRelation()
    {
        return $this->hasOne(Relation::class, ['person_id' => '__person_id', 'ref_person_id' => 'ref_person_id']);
    }

    public function getMaritalState()
    {
        return $this->hasOne(MaritalState::class, ['__person_id' => '__person_id', 'ref_person_id' => 'ref_person_id']);
    }
    public function getMaritalDocument()
    {
        return $this->hasOne(Document::class,['__ownableitem_id' => 'document_id'])->via('maritalState');

    }



}
