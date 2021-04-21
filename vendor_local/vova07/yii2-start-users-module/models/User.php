<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\plans\models\PlanItemGroup;
use vova07\users\Module;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class User extends  Ownableitem implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    //public static $dependsOn = Ident::class;

    public static function tableName()
    {
        return "user";
    }

    public function rules()
    {
        return [
            ['status_id', 'default', 'value' => self::STATUS_INACTIVE],
            ['status_id', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        return ArrayHelper::merge([
                'fields' => [
                    Helper::getRelatedModelIdFieldName(Ident::class) => Schema::TYPE_PK . ' ',
                    'username' => Schema::TYPE_STRING . '(30) NOT NULL',
                    'email' => Schema::TYPE_STRING . '(100) NOT NULL',
                    'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
                    'status_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                    'role' => Schema::TYPE_STRING,


                ],
                'indexes' => [
                    [self::class, 'username', true],
                    [self::class, 'email', true],
                    [self::class, 'status_id'],
                ],
                'dependsOn' => [
                    Ident::class,
                ]


            ]
            , parent::getMetaDataForMerging());

    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'ownableitem',
                    'ident',
                ],
            ]
        ];

    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getId()
    {
        $fieldName = Helper::getRelatedModelIdFieldName(Ident::class);
        return $this->$fieldName;
    }

    public function getAuthKey()
    {
        throw new NotSupportedException(
            'not yet realized ');

    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException(
            'not yet realized ');

    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findByUserName($username, $scope = "active")
    {
        $query = static::find()->where(['username' => $username]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public function getIdent()
    {
        return $this->hasOne(Ident::class, [Helper::getRelatedModelIdFieldName(Item::class) => Helper::getRelatedModelIdFieldName(Ident::class)]);
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getPerson()
    {
        //return $this->getIdent()->one()->getPerson();
        return $this->hasOne(Person::class,['__ownableitem_id' => 'person_id'])->viaTable(Ident::tableName(), ['__item_id' => '__ident_id']);
    }
    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_INACTIVE => Module::t('default','STATUS_INACTIVE'),
            self::STATUS_ACTIVE => Module::t('default','STATUS_ACTIVE'),

        ];
    }

    public static function getRolesForCombo()
    {
        return [
            \vova07\rbac\Module::ROLE_SUPERADMIN => \vova07\rbac\Module::ROLE_SUPERADMIN,
            \vova07\rbac\Module::ROLE_ADMIN => \vova07\rbac\Module::ROLE_ADMIN,
            \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT => \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_EXPERT,
            \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD => \vova07\rbac\Module::ROLE_LOGISTIC_AND_ADMINISTRATION_DEPARTMENT_HEAD,
            \vova07\rbac\Module::ROLE_FINANCE_DEPARTMENT_EXPERT => \vova07\rbac\Module::ROLE_FINANCE_DEPARTMENT_EXPERT,
            \vova07\rbac\Module::ROLE_FINANCE_DEPARTMENT_HEAD => \vova07\rbac\Module::ROLE_FINANCE_DEPARTMENT_HEAD,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,
            \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR => \vova07\rbac\Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,
            \vova07\rbac\Module::ROLE_COMPANY_HEAD => \vova07\rbac\Module::ROLE_COMPANY_HEAD,


        ];
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->select(['__ident_id','username'])->asArray()->all(),'__ident_id','username');
    }

    public function getOfficer()
    {
//        return $this->hasOne(Officer::class,[ '__person_id'  => '__ident_id']);
        //return $this->getPerson()->one()->officer;
        return $this->hasOne(Officer::class,['__person_id' => 'person_id'])->viaTable(Ident::tableName(), ['__item_id' => '__ident_id']);

    }

    public function getPlanGroup()
    {
        return PlanItemGroup::findOne(['role' => $this->role]);
    }
}
