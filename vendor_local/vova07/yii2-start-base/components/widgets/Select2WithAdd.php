<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/28/21
 * Time: 1:34 PM
 */

namespace vova07\base\components\widgets;


use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use yii\web\JsExpression;

class Select2WithAdd extends Select2
{
    public $newUrl;




    public function renderWidget()
    {
        $modalId = 'person_modal';
        $modalBeforeShow = ModalAjax::EVENT_BEFORE_SHOW;
        echo ModalAjax::widget([
    'id' => $modalId,
    //'url' => $this->newUrl,
    // 'selector' => 'a.btn_post_new', // all buttons in grid view with href attribute
    'events' => [
        ModalAjax::EVENT_MODAL_SUBMIT => new JsExpression(<<<JS
                function(event, data, status, xhr) {
                    if(status){
                        window.select2with_add.new_id = data.model.__ownableitem_id;
                        window.select2with_add.new_val = data.model.second_name +  ' ' + data.model.first_name + ' ' + data.model.patronymic;
                        $(this).modal('toggle');
                    }
                }
JS

                ),

        $modalBeforeShow => new JsExpression(<<<JS

       function(event, xhr, settings) {
        settings['url'] = modalUrl(settings['url'], {'suggestion':window.select2with_add.suggestion});
        } 
JS
        ),
        'hidden.bs.modal' => new JsExpression(<<<JS
     
        function(event, xhr, statusText){
            var newValue = new Option(window.select2with_add.new_val, window.select2with_add.new_id, true, true);
        // Append it to the select
        $("#" + window.select2with_add.select2_id).append(newValue).trigger('change');
           // alert('hidden.bs.modal');
        }
JS
)
    ],
    'header' => 'Create Person',
    'url' => $this->newUrl,
    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
    'size' => ModalAjax::SIZE_LARGE,
    'options' => ['class' => 'header-primary'],
    'autoClose' => true,
   // 'pjaxContainer' => '#grid_officer_posts-pjax',

]);
      $modalUrl = Url::to($this->newUrl)  ;
        $this->pluginEvents['select2:selecting'] = <<<JS
    function (e) {
        let selectedOption = e.data || e.params.args.data;

        // If the selected option is a tag we trigger a custom event and prevent this one never happened.
      
        if (selectedOption.isTag) {
           //e.preventDefault();
           // e.stopPropagation();
           // alert("test");
           window.select2with_add.suggestion = selectedOption.suggestion;      
          $('#$modalId').modal('show');
           
           //alert('modal');
           // $(e.currentTarget).trigger('select2:add-new', selectedOption);
        }
    }

JS
        ;



        $this->pluginOptions['tags'] = true;
        //$this->select2PluginOptions['multiple'] = true;
        $this->pluginOptions['insertTag'] = new JsExpression("function(data, tag){
            tag.isTag = true;
            tag.suggestion = tag.text;
            tag.text = \"Add: '\" + tag.text + \"'\";
            data.push(tag);
        }");

        $this->view->registerJs(<<<JS
            window.select2with_add = {
                'suggestion':null,
                'modal_id' : '$modalId',
                'select2_id': '{$this->options['id']}',
                'new_id':null,
                'new_val':null,
            }
JS
);

        parent::renderWidget(); // TODO: Change the autogenerated stub


    }

}