<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 25.10.2019
 * Time: 10:59
 */

namespace vova07\finances\models\backend;


use vova07\finances\models\BalanceCategory;
use vova07\finances\Module;
use vova07\users\models\Prisoner;
use yii\db\ActiveRecord;

class BalanceByPrisonerWithCategoryView extends ActiveRecord
{
    public static function tableName()
    {
        return 'vw_balance_by_prisoner_with_category';
    }
    public static function find()
    {
        return new BalanceByPrisonerWithCategoryViewQuery(get_called_class());

    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }

    public function attributeLabels()
    {
        $attributesArray = [
            'remain' => Module::t('default', 'REMAIN_LABEL'),
            'prisoner_id' => Module::t('labels','BALANCE_PRISONER_LABEL'),
            'only_debt' => Module::t('labels','BALANCE_ONLY_DEBT_LABEL')
        ];

        $categories = BalanceCategory::find()->all();
        foreach ($categories as $category)
        {
            $categoryFieldName = 'category'.$category->category_id;
            $attributesArray[$categoryFieldName] = $category->short_title;
        }
        return $attributesArray ;
    }


}