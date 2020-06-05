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
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Rank extends  ActiveRecordMetaModel
{


    public const RANK_INSPECTOR_PRINCIPAL_DE_JUSTITIE = 5;
    public const RANK_AGENT_SEF_ADJUNCT_DE_JUSTITIE = 10;
    public const RANK_COMISAR_PRINCIPAL_DE_JUSTITIE = 3;
    public const RANK_INSPECTOR_SUPERIOR_DE_JUSTITIE = 6;
    public const RANK_AGENT_SEF_DE_JUSTITIE = 9;
    public const RANK_AGENT_SUPERIOR_DE_JUSTITIE = 12;
    public const RANK_CHESTOR_DE_JUSTITIE = 1;
    public const RANK_AGENT_PRINCIPAL_DE_JUSTITIE = 11;
    public const RANK_COMISAR_SEF_DE_JUSTITIE = 2;
    public const RANK_COMISAR_DE_JUSTITIE = 4;
    public const RANK_INSPECTOR_DE_JUSTITIE = 7;
    public const RANK_AGENT_SEF_PRINCIPAL_DE_JUSTITIE = 8;

    public static function tableName()
    {
        return 'ranks';
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'id' => $migration->tinyInteger(3)->notNull(),
                'iso' => $migration->string(5),
                'title' => $migration->string()->notNull(),
                'rate' => $migration->double('2,2')

            ],
            'primaries' => [
                [self::class, 'id']
            ],




        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function rules()
    {
        return [
            [['id', 'title'],'required'],
            [['iso'],'string'],
            [['rate'],'number'],
           // [['company_id', 'title'],'unique'],
        ];
    }



    public static function find()
    {
        return new RankQuery(get_called_class());
    }

    public static function getListForCombo()
    {

        return ArrayHelper::map(self::find()->orderBy('title')->asArray()->all(),'id','title');


    }






}
