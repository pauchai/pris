<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\documents\models;


use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\ExpressionBuilder;

class DocumentQuery extends ActiveQuery
{


    public function expired()
    {
       return $this->andWhere(new Expression('NOW() > FROM_UNIXTIME(date_expiration)'));

    }
    public function notExpired()
    {
       return $this->andWhere(new Expression(' ISNULL(date_expiration) OR (NOT ISNULL(date_expiration) AND NOW() <= FROM_UNIXTIME(date_expiration))'));
    }

    public function aboutExpiration()
    {
        $this->andWhere( new Expression('NOT isnull(date_expiration)'));
        $this->andWhere(new Expression('NOW() < FROM_UNIXTIME(date_expiration)'));
        return $this->andWhere(new Expression('DateDiff( FROM_UNIXTIME(date_expiration), NOW()) <= :EXPIRATION_ABOUT_DAYS',[':EXPIRATION_ABOUT_DAYS' => Document::EXPIRATION_ABOUT_DAYS]));


    }

    public function byPerson($personId)
    {
        return $this->andWhere([
            'person_id' => $personId,
        ]);
    }
    public function activePrisoners()
    {
        $subQuery = Prisoner::find()->select('__person_id')->active();
        return  $this->andWhere(
            ['person_id' => $subQuery]
        );
    }
    public function foreigners()
    {
        $subQuery = Person::find()->select('__ident_id')->foreigners();
        return  $this->andWhere(
            ['person_id' => $subQuery]
        );
    }
    public function inProcess()
    {
        return $this->andWhere([
            'status_id' => Document::STATUS_IN_PROCESS
        ]);
    }
    public function active()
    {
        return $this->andWhere([
            'status_id' => Document::STATUS_ACTIVE
        ]);
    }

    public function identDocs()
    {
        return $this->andWhere([
            'type_id' => Document::$identDocIds
        ]);
    }




}