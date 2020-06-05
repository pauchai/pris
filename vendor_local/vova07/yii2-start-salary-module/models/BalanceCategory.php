<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;




use vova07\base\models\Ownableitem;
use vova07\salary\Module;



class BalanceCategory extends  Ownableitem
{

    const CATEGORY_SALARY = 1;
    const CATEGORY_WITHHOLDING = 2;
    const CATEGORY_CARD =3 ;

    public $id;

    public static function getListForCombo()
    {
        return [
            self::CATEGORY_SALARY => Module::t('default', 'CATEGORY_SALARY'),
            self::CATEGORY_WITHHOLDING => Module::t('default', 'CATEGORY_WITHHOLDING'),
            self::CATEGORY_CARD => Module::t('default', 'CATEGORY_CARD')
        ];
    }


}
