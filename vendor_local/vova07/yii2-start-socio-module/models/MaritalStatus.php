<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\socio\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class MaritalStatus extends  ActiveRecordMetaModel
{

    //use SaveRelationsTrait;

    const STATUS_MARRIAGE = 1;
    const STATUS_DIVORCE = 2;
    const STATUS_COHABITER = 3;
    const STATUS_NEVER_MARRIAGE = 4;
    const STATUS_WIDOWER = 5;





    public static function tableName()
    {
        return 'marital_status';
    }

    public function rules()
    {
        return [
            [['id', 'title'],'required'],

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
                'id' => $migration->integer()->notNull(),
                'title' => $migration->string(),


            ],
            'primaries' => [
                [self::class, ['id']]
            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }



    public static function find()
    {
        return new MaritalStateQuery(get_called_class());
    }




    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'title');
    }



}
