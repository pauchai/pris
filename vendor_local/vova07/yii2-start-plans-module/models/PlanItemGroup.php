<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use vova07\users\models\User;
use yii\base\Model;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class PlanItemGroup extends  ActiveRecordMetaModel
{
    const GROUP_SOCIOLOGIST_ID = 1;
    const GROUP_PSYCHOLOGIST_ID = 2;
    const GROUP_EDUCATOR_ID = 3;




    public function rules()
    {
        return [
            [['id','title', 'role'] ,'required']
        ];
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'id' => $migration->tinyInteger()->notNull(),
                'title' => $migration->string(),
                'role' => $migration->string(),

            ],

            'primaries' => [
                [self::class, 'id']
            ],
            'indexes' => [
                [self::class,['role'],true]
            ],



        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }

    public static function tableName()
    {
        return "plan_item_group";
    }

    public static function find()
    {
        return new PlanItemGroupQuery(get_called_class());
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'id','title');
    }

    public static function getRolesForCombo()
    {
        /*$roles = array_keys(\Yii::$app->authManager->getRoles()) ;
        $roles = array_map(function($item){
            return ['id' => $item,'title'=> $item];
        },$roles);
        return ArrayHelper::map($roles,'id','title');;*/
        return User::getRolesForCombo();
    }

    public static function findOneByRole($role)
    {
        return parent::findOne([
            'role' => $role
        ]);
    }

}
