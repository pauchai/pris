<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 2:41 PM
 */

namespace vova07\users\models\backend;


use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\base\Security;

class User extends \vova07\users\models\User
{
    const ROLE_DEFAULT = 'user';
    const SCENARIO_BACKEND_CREATE = 'backend-create';
    const SCENARIO_BACKEND_UPDATE = 'backend-update';
    const SCENARIO_BACKEND_CHANGE_PASSWORD = 'backend-change-password';
    public $password;
    public $repassword;

    public function rules(){
      return [
          [['username', 'email'],'required'],
          [['password', 'repassword'], 'required', 'on' => self::SCENARIO_BACKEND_CREATE],
          [['username', 'email', 'password', 'repassword'], 'trim'],
          // String
          [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
          // Unique
          [['username', 'email'], 'unique'],
          // Username
          ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
          ['username', 'string', 'min' => 3, 'max' => 30],
          // E-mail
          ['email', 'string', 'max' => 100],
          ['email', 'email'],
          // Repassword
          ['repassword', 'compare', 'compareAttribute' => 'password'],
      ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_BACKEND_CREATE => ['username', 'email', 'password', 'repassword', 'status_id','role'],
            self::SCENARIO_BACKEND_UPDATE => ['username', 'email', 'status_id','role'],
            self::SCENARIO_BACKEND_CHANGE_PASSWORD => ['password','repassword'],
        ];
    }

    public function beforeSave($insert)
    {

        if ( parent::beforeSave($insert)){

           $this->password_hash = static::generatePasswordHash($this->password);
           return true;

        }
          return false;

    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);



        $auth = \Yii::$app->authManager;
        $name = $this->role ? $this->role : self::ROLE_DEFAULT;
        $role = $auth->getRole($name);

        //if (!$insert) {
            $auth->revokeAll($this->primaryKey);
        //};

        $auth->assign($role, $this->primaryKey);
    }

    public static function generatePasswordHash($password)
    {
        $security = new Security();
        return $security->generatePasswordHash($password);
    }





}