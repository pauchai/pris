<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/1/19
 * Time: 9:56 AM
 */

namespace vova07\base\components;

use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use Yii;

class DateJuiBehavior extends AttributeBehavior
{
    public $_juiValue;
    /**
     * @var string attribute with timestamp to generate jui date value
     */
    public $attribute;
    /**
     * @var string attribute that will receive translated jui date value
     */
    public $juiAttribute;

    public $dateFormat = null;
    public $dateConvertFormat = null;
  //  public $preserveNonEmptyValues = true;

    public function init()
    {


        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->attribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attribute,
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attribute,
            ];
        }

    }

    public function __get($name)
    {
        if ($name === $this->juiAttribute){
           return  $this->getJuiValue();
        } else {
            parent::__get($name);
        }


    }

    public function __set($name, $value)
    {
        if ($name === $this->juiAttribute){
            $this->setJuiValue($value);
        } else {
            parent::__set($name, $value);
        }

    }
    protected function getJuiValue()
    {
        $attribute =$this->attribute;
        $juiAttribute = $this->juiAttribute;

        if ($this->_juiValue === null) {
            if (is_null($this->owner->$attribute)){
                $this->_juiValue = '';
            } else {
             //   $this->_juiValue = Yii::$app->formatter->asDate($this->owner->$attribute,$this->dateFormat);
                if (!$this->dateConvertFormat){
                    $dateTime = (new \DateTime())->setTimestamp($this->owner->$attribute);
                } else {
                    $dateTime = \DateTime::createFromFormat($this->dateConvertFormat,$this->owner->$attribute);
                }
                $this->_juiValue = $dateTime->format($this->dateFormat);
            }
        }
        return $this->_juiValue;
    }

    /**
     * Set jui created date
     */
    protected function setJuiValue($value)
    {
        $this->_juiValue = $value;
    }

    /**
     * Returns the value for the current attributes.
     * This method is called by [[evaluateAttributes()]]. Its return value will be assigned
     * to the attributes corresponding to the triggering event.
     * @param Event $event the event that triggers the current attribute updating.
     * @return mixed the attribute value
     */
    protected function getValue($event)
    {

                if ($this->value === null) {
                    if ($this->_juiValue){
                        $dateTime = \DateTime::createFromFormat($this->dateFormat,$this->_juiValue);
                        if (!$this->dateConvertFormat){

                            //return Yii::$app->formatter->asTimestamp($dateTime);
                            return $dateTime->getTimestamp();
                        } else {

                            //return Yii::$app->formatter->asDate($dateTime,$this->dateConvertFormat);
                            return $dateTime->format($this->dateConvertFormat);
                        }
                    } elseif (!is_null($this->_juiValue) ) {
                           return null;
                    } else {

                            $attr = $this->attribute;
                            return $this->owner->$attr;

                    }

                }
    }

    public function canGetProperty($name, $checkVars = true)
    {
         return $name === $this->juiAttribute;
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return $name === $this->juiAttribute;
    }
}


