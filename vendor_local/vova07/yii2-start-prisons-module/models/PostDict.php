<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\Module;
use vova07\salary\models\SalaryClass;
use yii\base\Model;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class PostDict extends  ActiveRecordMetaModel
{

    public static function tableName()
    {
        return 'post_dicts';
    }




    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                //Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'id' => $migration->smallInteger()->notNull(),
                'title' => $migration->string()->notNull(),
                'order' => $migration->smallInteger(),
                'rbac_role' => $migration->string(),
                'iso_id' =>  $migration->smallInteger()->notNull()
            ],
            'primaries' => [
                [self::class, ['id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'iso_id',PostIso::class,PostIso::primaryKey()],
            ],



        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }


    public function rules()
    {
        return [
            [['id', 'title', 'iso_id'],'required'],
            [[ 'title'],'string'],
            ['iso_id', 'integer']
            // [['company_id', 'title'],'unique'],
        ];
    }



    public static function find()
    {
        return new PostDictQuery(get_called_class());
    }


    public function getListForCombo()
    {

        return ArrayHelper::map(self::find()->orderBy('title')->asArray()->all(),'id','title');

    }
    public function getPostIso()
    {
        return $this->hasOne(PostIso::class, ['id' => 'iso_id']);
    }

    public function attributeLabels()
    {
        return [
            'title' => Module::t('default', 'POSTDICT_TITLE')
        ];
    }
}
