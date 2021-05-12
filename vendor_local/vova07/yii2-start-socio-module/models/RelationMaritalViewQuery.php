<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\socio\models;


use vova07\documents\models\DocumentQuery;
use yii\db\ActiveQuery;
use yii\db\Expression;
use vova07\documents\models\Document;
use yii\helpers\ArrayHelper;

class RelationMaritalViewQuery extends ActiveQuery
{
   public function withMaritalDocument()
   {

       return $this->joinWith('maritalDocument');

   }
   public function mergeDocumentQuery(DocumentQuery $query)
   {
        $this->withMaritalDocument();
         if ($this->where === null) {$this->where = [];}
         if ($query->where instanceof  Expression){
             $query->where=[ $query->where];
         }
           $this->where = ArrayHelper::merge($this->where, $query->where);
         return $this;

   }
    //From document
    public function expired()
    {
        return $this->andWhere(new Expression('NOW() > DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_expiration SECOND)'));

    }
    public function notExpired()
    {

        return $this->andWhere(new Expression(' ISNULL(date_expiration) OR (NOT ISNULL(date_expiration) AND NOW() <= DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_expiration SECOND))'));
    }

    public function aboutExpiration()
    {
        $this->andWhere( new Expression('NOT isnull(date_expiration)'));
        $this->andWhere(new Expression('NOW() < FROM_UNIXTIME(date_expiration)'));
        return $this->andWhere(new Expression('DateDiff( FROM_UNIXTIME(date_expiration), NOW()) <= :EXPIRATION_ABOUT_DAYS',[':EXPIRATION_ABOUT_DAYS' => Document::EXPIRATION_ABOUT_DAYS]));
    }

    public function documentActive()
    {
        return $this->andWhere([
            'document.status_id' => Document::STATUS_ACTIVE
        ]);
    }
}