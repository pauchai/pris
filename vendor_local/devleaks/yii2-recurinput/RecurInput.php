<?php

namespace devleaks\recurinput;

use Yii;
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
        $this->registerClientScript();
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
		
        if ($this->inline) {
            $input .= '<div></div>';
        }
        if ($this->addons && !$this->inline) {
			$addons = '';
			$addons .= Html::button( '<i class="glyphicon glyphicon-repeat"></i>', ['name' => 'riedit', 'class' => 'input-group-addon']);
			$addons .= Html::button( '<i class="glyphicon glyphicon-remove"></i>', ['name' => 'ridelete', 'class' => 'input-group-addon']);
            $input = strtr($this->template, ['{input}' => $input, '{addon}' => $addons]);
            $input = Html::tag('div', $input, $this->containerOptions);
        }
        if ($this->inline) {
            $input = strtr($this->template, ['{input}' => $input, '{addon}' => '']);
        }
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

    /**
     * Registers required script for the plugin to work as DatePicker
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        RecurInputAsset::register($view);

        $js = [];
        $id = $this->options['id'];
        $js[] = "/*begin recurinput*/";
        $js[] = ";";
        $selector = "jQuery('#$id')";

        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';

        $js[] = "$selector.recurrenceinput({startField: 'round-start_date', template: {display: '#mytemplate' } });";

		if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "$selector.on('$event', $handler);";
            }
        }
        $js[] = "/*end recurinput*/";
        $view->registerJs(implode("\n", $js));
    }

}