<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\prisons\Module;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;

use yii\db\ActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class Penalty extends  Ownableitem
{

    const EXPIRATION_ABOUT_DAYS = 30;

    public static function tableName()
    {
        return 'penalties';
    }

    public function rules()
    {
        return [
            [['prison_id', 'prisoner_id',  'dateStartJui', 'dateFinishJui'], 'required'],
            [['comment'] , 'string'],

            [['dateStartJui', 'dateFinishJui'], 'date']
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
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prisoner_id' => $migration->integer()->notNull(),
                'prison_id' => $migration->integer()->notNull(),
                'date_start' => $migration->bigInteger()->notNull(),
                'date_finish' => $migration->bigInteger()->notNull(),
                'comment' => $migration->string(),
            ],
            'indexes' => [
                [self::class, 'prison_id'],
                [self::class, 'prisoner_id'],
                [self::class, 'date_start'],
                [self::class, 'date_finish'],
            ],
            'foreignKeys' => [
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],
                [self::class, 'prison_id', Prison::class, Prison::primaryKey()],


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
            $behaviors = [

            ];
        }

        $behaviors = ArrayHelper::merge($behaviors,[
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_start',
                'juiAttribute' => 'dateStartJui',

            ],
            [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_finish',
                'juiAttribute' => 'dateFinishJui',

            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new PenaltyQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'comment');
    }



    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id'=>'prisoner_id']);
    }
    public function getPrison()
    {
        return $this->hasOne(Prison::class, ['__company_id'=>'prison_id']);
    }



    public function isExpired()
    {
        if ($this->date_end){
            return time()>$this->date_end;
        } else {
            return false;
        }
    }
    public function isAboutExpiration()
    {
        if ($this->date_end && $this->date_end> time())
        {
            $seconds =  $this->date_end - time();
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($this->date_end);

            $dateDiff = $dateTime->diff(new \DateTime());
            return $dateDiff->days < self::EXPIRATION_ABOUT_DAYS;

        }
    }

    public function attributeLabels()
    {
        return [
            'prisoner_id' => Module::t('labels','PRISONER_FIO_LABEL'),
            'dateStartJui' => Module::t('labels','PENALTY_DATE_START_LABEL'),
            'dateEndJui' => Module::t('labels','PENALTY_DATE_END_LABEL'),

        ];
    }

    // overrides Item::delete
    public function delete()
    {
       return ActiveRecord::delete();
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id' => 'prisoner_id']);
    }

}
