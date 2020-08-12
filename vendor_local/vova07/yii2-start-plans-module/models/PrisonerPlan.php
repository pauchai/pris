<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\comments\models\Comment;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use vova07\users\models\Officer;


class PrisonerPlan extends  Ownableitem
{


    const STATUS_ACTIVE =1;
    const STATUS_REALIZED =10;
    const STATUS_REFUSED =12;



    public static function tableName()
    {
        return 'prisoner_plans';
    }
    public function rules()
    {
        return [
            [['__prisoner_id','status_id'],'required'],
            [['assignedAtJui', 'dateFinishedJui'],'date'],
            [['status_id'],'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $primaryName =  Helper::getRelatedModelIdFieldName(Prisoner::class);
        $migration = new Migration();
        $metadata = [
            'fields' => [
                $primaryName => Schema::TYPE_PK . ' ',
                'assigned_to' => $migration->integer(),
                'assigned_at' => $migration->bigInteger(),
                'date_finished' => $migration->bigInteger(),
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL',
            ],
            'dependsOn' => [
                Prisoner::class
            ],
            'index' => [
                [self::class,['status_id']]
            ],
            'foreignKeys' => [
                [self::class, $primaryName,Prisoner::class, Prisoner::primaryKey()],
                [self::class, ['assigned_to'],Officer::class, Officer::primaryKey()]


            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

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
            'dateFinishedJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'date_finished',
                'juiAttribute' => 'dateFinishedJui'

            ],
            'assignedAtJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'assigned_at',
                'juiAttribute' => 'assignedAtJui'

            ]
        ]);

        return $behaviors;
    }

    public static function find()
    {
        return new PrisonerPlanQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => '__prisoner_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id' => '__prisoner_id']);
    }

    /**
     * @return ProgramPrisonerQuery
     */
    public function getPrisonerPrograms()
    {
        return $this->hasMany(ProgramPrisoner::class, ['prisoner_id' => '__prisoner_id']);
    }

    /**
     * @return RequirementsQuery
     */
    public function getRequirements()
    {
        return $this->hasMany(Requirement::class, ['prisoner_id' => '__prisoner_id']);
    }


    public  function getComments()
    {

        return $this->hasMany(Comment::class, ['item_id' => '__ownableitem_id']);

    }

    public function getItem()
    {
        return $this->ownableitem->item;
    }
/*    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->with('programDict')->asArray()->all(),'__ownableitem_id','title');
    }*/

    public static function getStatusesForCombo()
    {
        return [
          self::STATUS_ACTIVE => Module::t('default','STATUS_ACTIVE'),
          self::STATUS_REALIZED => Module::t('default','STATUS_REALIZED'),
          self::STATUS_REFUSED => Module::t('default','STATUS_REFUSED'),
        ];
    }

    public function getStatus()
    {

        return ArrayHelper::getValue(self::getStatusesForCombo(),$this->status_id);
    }

    public function attributeLabels()
    {
        return [
            'assignedAtJui' => Module::t('labels','ASSIGNED_AT'),
            'assigned_to' => Module::t('labels','ASSIGNED_TO'),
            'dateFinishedJui' => Module::t('labels','DATE_FINISHED'),
            'date_finished' => Module::t('labels','DATE_FINISHED'),
            'status_id' => Module::t('labels','STATUS'),
            'fio' => Module::t('labels', 'FIO')

        ];
    }
}
