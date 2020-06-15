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


class PostIso extends  ActiveRecordMetaModel
{
  const POST_COMPANY_HEAD = 1;
  const POST_COMPANY_HEAD_ASSISTENT = 2;
  const POST_HEAD_SECTION = 3;
  const POST_HEAD_SERVICE = 4;
  const POST_HEAD_GROUP = 5;
  const POST_HEAD_GROUP_ASSISTENT = 6;
  const POST_HEAD_POST = 7;
  const POST_OFFICER_PRINCIPAL = 8;
  const POST_OFFICER_LEADER = 9;
  const POST_OFFICER = 10;
  const POST_AGENT_PRINCIPAL = 11;
  const POST_AGENT_LEADER = 12;
  const POST_AGENT = 13;
  const POST_SPECIALIST_PRINCIPAL_ = 14;
  const POST_HEAD_DEPOT = 15;
  const POST_MEDIC_PSYCHIATRIST  = 16;
  const POST_MEDIC_DANTIST = 17;
  const POST_PHARMACIST = 18;

    public static function tableName()
    {
        return 'post_iso';
    }




    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                //Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'id' => $migration->smallInteger()->notNull(),
                'code' => $migration->string(5),
                'title' => $migration->string()->notNull(),
                'order' => $migration->smallInteger(),
                'salary_class_id' => $migration->smallInteger(),
            ],
            'primaries' => [
                [self::class, ['id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'salary_class_id',SalaryClass::class,SalaryClass::primaryKey()],
            ],



        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }


    public function rules()
    {
        return [
            [['id', 'title'],'required'],
            [['code', 'title'],'string'],
            ['salary_class_id', 'integer']
            // [['company_id', 'title'],'unique'],
        ];
    }



    public static function find()
    {
        return new PostIsoQuery(get_called_class());
    }


    public  static function getListForCombo()
    {

        return ArrayHelper::map(self::find()->orderBy('title')->asArray()->all(),'id','title');

    }
    public function getSalaryClass()
    {
        return $this->hasOne(SalaryClass::class, ['id' => 'salary_class_id']);
    }

}
