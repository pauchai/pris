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
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ProgramVisit extends  Ownableitem
{
    const STATUS_DOESNT_PRESENT = 0;
    const STATUS_DOESNT_PRESENT_VALID = 9;
    const STATUS_PRESENT = 10;


    public static function tableName()
    {
        return 'program_visits';
    }

    public function rules()
    {
        return [
            [['program_prisoner_id',  'status_id', 'date_visit'], 'required'],
            [['date_visit'], 'date', 'format' => 'yyyy-MM-dd'],

        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'program_prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL ',
                'date_visit' => Schema::TYPE_DATE . ' NOT NULL',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL'
            ],

           // 'primaries' => [
            //    [self::class, ['program_id', 'prisoner_id', 'date_visit']]
            //],

            'index' => [

                [get_called_class(), ['status_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), ['program_prisoner_id'], ProgramPrisoner::class, ProgramPrisoner::primaryKey()],
                //[get_called_class(), 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],

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
                        //'program',
                        //'prisoner'


                    ],
                ]

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new ProgramVisitQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getProgram()
    {
        return $this->hasOne(Program::class, ['__ownableitem_id' => 'program_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id' => 'prisoner_id']);
    }

    public function getProgramPrisoner()
    {
        return $this->hasOne(ProgramPrisoner::class, [
            '__ownableitem_id' => 'program_prisoner_id',

        ]);
    }


    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_DOESNT_PRESENT => Module::t('programs', 'PROGRAM_VISIT_DOESNT_PRESENT'),
            self::STATUS_DOESNT_PRESENT_VALID => Module::t('programs', 'PROGRAM_VISIT_DOESNT_PRESENT_VALID'),
            self::STATUS_PRESENT => Module::t('programs', 'PROGRAM_VISIT_RESENT'),
        ];
    }

    public function getStatus()
    {
        $statuses = self::getStatusesForCombo();
        return $statuses[$this->status_id];
    }

    public static function mapStatuseStyle()
    {
        return [
            self::STATUS_DOESNT_PRESENT => 'danger',
            self::STATUS_DOESNT_PRESENT_VALID => 'info',
            self::STATUS_PRESENT => 'success',
        ];
    }
    public function resolveStatusStyle()
    {
        return self::mapStatuseStyle()[$this->status_id];
    }



}
