<?php

namespace pauk\recurinput;

use kartik\form\ActiveForm;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 *
 */
class RecurInput extends InputWidget
{
    /**
    * @var string plugin name
     */
    private $_pluginName = 'recurInput';
    
    /**
     * @var string the addon markup if you wish to display the input as a component. If you don't wish to render as a
     * component then set it to null or false.
     */
    public $addons = ['<i class="glyphicon glyphicon-repeat"></i>','<i class="glyphicon glyphicon-remove"></i>'];

    /**
     * @var string the template to render the input.
     */
    public $template = '{input}';

    /**
     * @var bool whether to render the input as an inline form
     */
    public $inline = false;

    /**
     * @var string Bootstrap size (sm, md, lg...)
     */
	public $size;

    /**
     * @var array HTML attributes to render on the container
     */
    public $containerOptions = [];

    /**
     * @var array options for the plugin
     */
	public $pluginOptions;

    /**
     * @var array HTML attributes for the displayed input fields
     */
    private $_displayOptions = [];


    public $recurModel;

    /**
     * @inherit doc
     */
    public function init()
    {
        parent::init();

        if ($this->inline) {
            $this->options['readonly'] = 'readonly';
            Html::addCssClass($this->options, 'text-center');
        }
        if ($this->size) {
            Html::addCssClass($this->options, 'input-' . $this->size);
            Html::addCssClass($this->containerOptions, 'input-group-' . $this->size);
        }
        Html::addCssClass($this->options, 'form-control');
        Html::addCssClass($this->containerOptions, 'input-group repeat');

        $this->_displayOptions = $this->options;
        $this->_displayOptions['id'] .= '-recurinput';
        if (isset($this->_displayOptions['name'])) {
            unset($this->_displayOptions['name']);
        }

//        $this->renderInput();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $name = $this->options['id'];
        $this->options['readonly'] = 'readonly';
        $input = $this->hasModel() ?
		   Html::activeTextInput($this->model, $this->attribute, $this->options) :
		   Html::textInput($this->name, $this->value, $this->options);



      $this->renderModal();

        echo $input;
    }

    /**
     * Renders hidden field that contains actual model value
     */
    protected function renderInput()
    {
		echo $this->hasModel() ?
		   Html::activeHiddenInput($this->model, $this->attribute, $this->options) :
		   Html::hiddenInput($this->name, $this->value, $this->options);
		// renders hidden div with form
    }

    protected function renderModal()
    {
        Modal::begin([
            'toggleButton' => ['label' => 'click me'],
            'size' => 'modal-lg',
            'options' => [
                'id' => 'modal_rrule'.$this->model->primaryKey,
            ],
            'header' => '<h2>Create Vote</h2>',
            'footer' => 'Footer'
        ]);

       $form = ActiveForm::begin([
            'action' => 'vote/vote',
            'method' => 'post',
            'id' => 'form'.$this->model->primaryKey
        ]);

        echo $form->field($this->recurModel, 'frequency')->dropDownList(RecurInputForm::getFrequenciesList());

        echo Html::submitButton(
            '<span class="glyphicon glyphicon-search"></span>',
            [
                'class' => 'btn btn-success',
            ]
        );
        ActiveForm::End();
        Modal::end();
    }


}