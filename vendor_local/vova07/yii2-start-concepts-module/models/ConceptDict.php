<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\concepts\models;




use vova07\base\models\ActiveRecordMetaModel;
use yii\behaviors\SluggableBehavior;
use yii\db\Migration;
use yii\helpers\ArrayHelper;




class ConceptDict extends  ActiveRecordMetaModel
{

    public static function tableName()
    {
        return 'concept_dicts';
    }

    public function rules()
    {
        return [
            [['title'],'required']

        ];

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'title',
                    'slugAttribute' => 'slug',

                    'ensureUnique' => true,
                ],


            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }
    public static function getMetadata()
    {

        $migration = new Migration();
        $metadata = [
            'fields' => [
                'id' => $migration->primaryKey(),
                'title' => $migration->string(),
                'slug' => $migration->string(),

            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public static function find()
    {
        return new ConceptDictQuery(get_called_class());
    }



    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'title');
    }



}
