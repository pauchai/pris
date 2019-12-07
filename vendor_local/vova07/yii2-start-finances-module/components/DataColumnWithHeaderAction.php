<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 26.10.2019
 * Time: 11:32
 */
namespace vova07\finances\components;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
class DataColumnWithHeaderAction extends \yii\grid\DataColumn
{
    public $buttonLabel = 'Action';
    public $buttonItems = [];
    public function renderHeaderCell()
    {
        return Html::tag('th', $this->renderHeaderCellContent(). $this->renderActionButton(), $this->headerOptions);
    }

    public function renderActionButton()
    {
       return  ButtonDropdown::widget([
      'label' => $this->buttonLabel,
      'dropdown' => [
          'items' => $this->buttonItems,
      ],
        ]);
    }

}