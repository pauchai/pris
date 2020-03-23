<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\psycho\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\psycho\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;

class PsyTest extends  Ownableitem
{



    public static function tableName()
    {
        return 'psy_tests';
    }

    public function rules()
    {
        return [
            [['prisoner_id', 'atJui'],'required'],


        ];

    }


    public static function getMetadata()
    {

        $migration = new Migration();
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prisoner_id' => $migration->integer()->notNull(),
                'at' => $migration->date()->notNull(),
            ],
            'indexes' => [
                [self::class, 'at'],
            ],

            'foreignKeys' => [
                [get_called_class(), 'prisoner_id',Prisoner::class,Prisoner::primaryKey()],
            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                    ],
                ],

            ];
        } else {
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'at',
                'juiAttribute' => 'atJui'

            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new PsyTestQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id'=>'prisoner_id']);
    }

    public function getPrisonerFio()
    {
        return $this->prisoner->person->fio;
    }



}
