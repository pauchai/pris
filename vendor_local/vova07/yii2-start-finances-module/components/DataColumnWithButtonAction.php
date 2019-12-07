<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 26.10.2019
 * Time: 11:32
 */
namespace vova07\finances\components;
use vova07\finances\models\backend\BalanceSearch;
use vova07\finances\models\BalanceCategory;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
class DataColumnWithButtonAction extends \yii\grid\DataColumn
{
    public $headerActionUrl = ['/finances/balance/index'];
    public $dataCellActionUrl = ['/finances/balance/index'];
    public $attributePattern = 'category(\d+)';
    public $enableResolveCategoryAndType = true;
    public $category_id;
    public $type_id;
    //public $options = ['class'=>' btn-default fa fa-plus'];

    public function renderHeaderCell()
    {
        return Html::tag('th', $this->renderHeaderActionButton().$this->renderHeaderCellContent()  , $this->headerOptions);
    }

    public function renderDataCellContent($model, $key, $index)
    {
        //  $content =  parent::renderDataCellContent($model,$key,$index);

            $attribute = $this->attribute;
            if (is_null($model->$attribute)){
                $content = Html::a(Html::tag('i',null,['class'=>'fa fa-plus']), $this->getDataCellActionUrl($model,$key,$index), $this->options);
            } else {
                $content = Html::a($model->$attribute, $this->getDataCellActionUrl($model,$key,$index), $this->options);
            }


        return $content;
    }


    public function renderHeaderActionButton()
    {
        return Html::tag('div',Html::a('', $this->getHeaderActionUrl(), ['class' => 'btn btn-default fa fa-plus']));
    }

    public function getHeaderActionUrl()
    {
        if (!($this->category_id || $this->type_id) && $this->enableResolveCategoryAndType)
            $this->resolveCategoryAndType();
        $balanceSearch = new BalanceSearch();
        $actionUrl = ArrayHelper::merge(
            $this->headerActionUrl,
            [
                $balanceSearch->formName() => ['category_id'=>$this->category_id,'type_id'=>$this->type_id]
            ]
        );

        return \yii\helpers\Url::to($actionUrl);
    }


    public function getDataCellActionUrl($model, $key, $index)
    {
            if (!($this->category_id || $this->type_id) && $this->enableResolveCategoryAndType)
                $this->resolveCategoryAndType();
            $balanceSearch = new BalanceSearch();
            $actionUrl = ArrayHelper::merge(
                $this->dataCellActionUrl,
                [
                    $balanceSearch->formName() => ['prisoner_id'=>$model->prisoner_id, 'category_id'=>$this->category_id,'type_id'=>$this->type_id]
                ]
            );

                return \yii\helpers\Url::to($actionUrl);
    }

    private function resolveCategoryAndType()
    {
        $matches = [];
        if (preg_match('#' . $this->attributePattern . '#',$this->attribute,$matches)===1){
            $this->category_id = $matches[1];
            $category = BalanceCategory::findOne(['category_id' => $this->category_id]);
            if ($category){
                $this->type_id = $category->type_id;
            }

        } else {
            throw new \LogicException('category doesnt match by pattern attribute '. $this->attribute);
        }

    }




}