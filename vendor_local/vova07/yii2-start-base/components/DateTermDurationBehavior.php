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

class DateTermDurationBehavior extends AttributeBehavior
{
    private $_durationValue;
    /**
     * @var string attribute with timestamp to generate jui date value
     */
    public $attributeTermStart;
    public $attributeTermFinish;
    public $durationAttribute;



  //  public $preserveNonEmptyValues = true;

    public function init()
    {


        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->attributeTermFinish,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attributeTermFinish,
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attributeTermFinish,
            ];
        }

    }

    public function __get($name)
    {
        if ($name === $this->durationAttribute){
           return  $this->getDurationValue();
        } else {
            parent::__get($name);
        }


    }

    public function __set($name, $value)
    {
        if ($name === $this->durationAttribute){
            $this->setDurationValue($value);
        } else {
            parent::__set($name, $value);
        }

    }
    protected function getDurationValue()
    {
        $attributeTermStart =$this->attributeTermStart;
        $attributeTermFinish =$this->attributeTermFinish;
        $durationAttribute = $this->durationAttribute;

        if ($this->_durationValue === null) {
            if (is_null($this->owner->attributeTermStart) || is_null($this->owner->attributeTermFinish) ){
                $this->_durationValue = '';
            } else {
                $termStartDate = \DateTime::createFromFormat('yyyy-mm-dd',$this->owner->$attributeTermStart );
                $termFinishDate = \DateTime::createFromFormat('yyyy-mm-dd',$this->owner->$attributeTermFinish );

                $this->_durationValue = date_diff($termStartDate, $termFinishDate)->format('Y-M-D');


            }
        }
        return $this->_durationValue;
    }

    /**
     * Set jui created date
     */
    protected function setDurationValue($value)
    {
        $this->_durationValue = $value;
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
        $attributeTermStart =$this->attributeTermStart;

        $durationAttribute = $this->durationAttribute;
                if ($this->value === null) {
                    if ($this->__durationValue){
                        $termStartDate = \DateTime::createFromFormat('yyyy-mm-dd', $this->owner->$attributeTermStart);
                        list($durationYear, $durationMonth, $durationDay) = preg_split('/-/',$this->owner->$durationAttribute);

                        $termFinishDate = (clone $termStartDate)->add('P' . $durationYear . 'Y' . $durationMonth . 'M' . $durationDay . 'D');
                        return $termFinishDate->format('yyyy-mm-dd');

                    } else {
                        $attr = $this->durationAttribute;
                        return $this->owner->$attr;
                    }

                }
    }

    public function canGetProperty($name, $checkVars = true)
    {
         return $name === $this->durationAttribute;
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return $name === $this->durationAttribute;
    }
}


