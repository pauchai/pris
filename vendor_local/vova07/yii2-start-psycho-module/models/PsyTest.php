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

    const STATUS_ID_SUCCESS = 1;
    const STATUS_ID_REFUSED = 9;

    const STATUS_GREEN = 'success';
    const STATUS_BLUE = 'info';
    const STATUS_RED = 'danger';

    const LIMIT_MONTHS_GREEN = 11;
    const LIMIT_MONTHS_BLUE = 12;


    public static function tableName()
    {
        return 'psy_tests';
    }

    public function rules()
    {
        return [
            [['prisoner_id', 'atJui', 'status_id'],'required'],


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
                'status_id' => $migration->tinyInteger()->notNull()
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

    public function getStatusGlyph()
    {

            $dateTime =  \DateTime::createFromFormat('Y-m-d', $this->at);
            $dateDiff = $dateTime->diff(new \DateTime());

            $diffMonths = $dateDiff->m + $dateDiff->y * 12;
            if ( $diffMonths <= self::LIMIT_MONTHS_GREEN )
                $res = self::STATUS_GREEN;
            elseif ( $diffMonths > self::LIMIT_MONTHS_GREEN && $diffMonths <= self::LIMIT_MONTHS_BLUE)
                $res = self::STATUS_BLUE;
            elseif ( $diffMonths > self::LIMIT_MONTHS_BLUE)
                $res = self::STATUS_RED;

            return $res;

     }

     public static function getStatusesForCombo()
     {
         return [
             self::STATUS_ID_SUCCESS => Module::t('default','STATUS_SUCCESS_LABEL'),
             self::STATUS_ID_REFUSED => Module::t('default','STATUS_REFUSED_LABEL'),
         ];
     }

     public function getStatus()
     {
         return ArrayHelper::getValue(self::getStatusesForCombo(), $this->status_id);
     }


}
