<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/1/19
 * Time: 5:52 PM
 */

namespace vova07\users\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use yii\base\NotSupportedException;
use yii\db\Schema;
use yii\web\IdentityInterface;

/**
 * Class Ident
 * @package vova07\users\models
 */
class Ident extends Item implements IdentityInterface
{
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return "ident";
    }
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Item::class) => Schema::TYPE_PK . ' ',
                'status_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT ' . self::STATUS_ACTIVE ,

            ],
            'indexes' => [
                [self::class, 'status_id'],
            ],


        ];
        return $metadata;


    }

    public function rules()
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'saveRelations' =>[
                'class'     => SaveRelationsBehavior::class,
                'relations' => [
                    'item',
                ],
            ]
        ];
    }






    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getId()
    {
        return $this->primaryKey;
    }

    public function getAuthKey()
    {
        throw new NotSupportedException(
            'not yet realized ');

    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException(
            'not yet realized ');

    }

    public function getItem()
    {
        return $this->hasOne(Item::class,['id' => '__item_id']);
    }


    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'__item_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::class,['__ident_id'=>'__item_id']);
    }

}