<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/1/19
 * Time: 5:47 PM
 */

namespace vova07\base\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelsFactory;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Migration;
use yii\db\Schema;

class Item extends ActiveRecordMetaModel
{

    public static function tableName()
    {
        return "item";
    }

    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'id' => Schema::TYPE_PK . ' ',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL' ,
                'updated_at' =>  Schema::TYPE_INTEGER . ' NOT NULL' ,

            ],
            'indexes' => [
                [get_called_class(), 'created_at'],
            ],


        ];
        return $metadata;

    }


    public function behaviors()
    {
        if (get_called_class() === self::class) {
            return [
                'timestampBehavior' => [
                    'class' => TimestampBehavior::class,
                ],
            ];
        } else {
            return [];
        }


    }

    public function delete()
    {
        if (get_called_class() == Item::class){
            return parent::delete();
        } else {
            if (is_array($this->primaryKey))
                return parent::delete();
            else {
                $item = Item::findOne($this->primaryKey);
                return $item->delete();
            }
        }

    }











}