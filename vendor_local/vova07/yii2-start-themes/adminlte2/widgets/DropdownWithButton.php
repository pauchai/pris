<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/29/19
 * Time: 12:48 PM
 */

namespace vova07\themes\adminlte2\widgets;


use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\jui\DatePicker;

class DropdownWithButton extends InputWidget
{
    public $items = [];
    public $submitText='';
    public $buttonInputOptions = ['class'=>'btn btn-info btn-flat fa fa-plus'];
    public $containerOptions = 'input-group input-group-sm';
    public $itemsOptions = ['style'=>'width:100%','class'=>'form-control','prompt'=>"SELECT"];
    public function run()
    {
        return $this->renderWidget();
    }
    public function renderWidget()
    {
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
            return Html::tag('div',
                Html::activeDropDownList($this->model,$this->attribute,$this->items,$this->itemsOptions).
                Html::tag('span',
                    Html::submitButton($this->submitText,$this->buttonInputOptions),
                ['class'=>'input-group-btn']
                    ),
            ['class'=>$this->containerOptions]
                );


//        return <<<HTML
//<div class="input-group input-group-sm">
//                <input class="form-control" type="text">
//                    <span class="input-group-btn">
//                      <button type="button" class="btn btn-info btn-flat">Go!</button>
//                    </span>
//              </div>
//HTML;


    }

}