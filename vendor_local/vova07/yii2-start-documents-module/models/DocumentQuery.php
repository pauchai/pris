<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/28/19
 * Time: 6:35 PM
 */

namespace vova07\documents\models;


use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\ExpressionBuilder;

class DocumentQuery extends ActiveQuery
{


    public function expired()
    {
       return $this->andWhere(new Expression('NOW() >= FROM_UNIXTIME(date_expiration)'));

    }

    public function aboutExpiration()
    {
        $this->andWhere( new Expression('NOT isnull(date_expiration)'));
        $this->andWhere(new Expression('NOW() < FROM_UNIXTIME(date_expiration)'));
        return $this->andWhere(new Expression('DateDiff( FROM_UNIXTIME(date_expiration), NOW()) <= :EXPIRATION_ABOUT_DAYS',[':EXPIRATION_ABOUT_DAYS' => Document::EXPIRATION_ABOUT_DAYS]));


    }

}