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
use vova07\users\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class RelationType extends  ActiveRecordMetaModel
{

    const ID_PARENTS = 1;
    const ID_CHILDREN = 2;
    const ID_PARTNER= 3;
    const ID_BROTHER_SISTER = 4;
    const ID_FRIEND =5;
    #




    public static function tableName()
    {
        return 'relation_type';
    }

    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['title'],'required']
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
                'parent_id' => $migration->integer(),

            ],
            'primaries' => [
                [self::class, 'id']
            ],
            'foreignKeys' => [
               // [get_called_class(), 'parent_id',self::class,self::primaryKey()],

            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                ],
            ]
        ];

    }

    public static function find()
    {
        return new RelationTypeQuery(get_called_class());
    }

    public function getParent()
    {
        return $this->hasOne(Self::class,[ 'id'  => 'parent_id']);
    }

    public static function getListForCombo()
    {
        //return ArrayHelper::map(self::find()->asArray()->all(),'__ident_id','first_name');
        return ArrayHelper::map(self::find()->select(['id', 'title' ])->asArray()->all(),'id','title');
    }

    public function attributeLabels()
    {
        return [
            'title' => Module::t('labels', "RELATION_TYPE_TITLE_LABEL"),

        ];
    }

}
