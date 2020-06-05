<?php
namespace vova07\base\components\widgets;
use yii\bootstrap\Html;
use yii\jui\InputWidget;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 5/28/20
 * Time: 10:54 AM
 */

class DepInput extends InputWidget
{
    public $depends;

    public function run()
    {
       // $this->registerWidget('depinput');
        $fieldId = $this->options['id'];
        $this->getView()->registerJs(<<<JS
        $('#' + '$this->depends').on('change',function(){
            $("#$fieldId").val($("#$this->depends").children("option:selected").text())
        })
JS
);
        return $this->renderWidget();
    }

    public function renderWidget()
    {
        if ($this->hasModel()) {
            return Html::activeTextInput($this->model, $this->attribute, $this->options);
        }
        return Html::textInput($this->name, $this->value, $this->options);
    }


}