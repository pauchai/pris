<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Prison extends  OwnableItem
{
    use SaveRelationsTrait;

    public static function tableName()
    {
        return 'prison';
    }

    public function rules()
    {
        return [];
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Company::class) => Schema::TYPE_PK . ' ',
            ],
            'dependsOn' => [
                Company::class
            ]

        ];
        return $metadata;
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'ownableitem',
                    'company',
                    'sectors'
                ],
            ]
        ];
    }


    public static function find()
    {
        return new PrisonQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getCompany()
    {
        return $this->hasOne(Company::class,['__ownableitem_id' => '__company_id']);
    }
    public function getSectors()
    {
        return $this->hasMany(Sector::class,['prison_id'=>'__company_id']);
    }
    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->joinWith('company')->asArray()->all(),'__company_id','company.title');
    }



}
