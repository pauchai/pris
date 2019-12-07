<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 02.10.2019
 * Time: 11:20
 */

namespace vova07\base\components;


use yii\base\BaseObject;
use yii\base\Exception;

class CsvFile extends BaseObject
{
    public $firstRowAsIndex = true;
    public $fileName;
    public $cellDelimiter = "\t";
    protected $fileHandler;
    protected $cellNameIndexMap;
    protected $_elements;


    public function init()
    {
        parent::init();
        if ($this->firstRowAsIndex) {
            $this->loadFirstRow();
        }

    }

    public function loadFirstRow()
    {
        $arr = $this->readArray();

        $arr = array_map(function($item){
                     return trim($item);
            }, $arr);
        $this->cellNameIndexMap = array_flip($arr);
    }


    public function readArray()
    {
        $this->open();
        return fgetcsv($this->fileHandler, 0, $this->cellDelimiter);
    }

    public function read()
    {
        $this->_elements = $this->readArray();
        return $this;
    }

    public function getField($key)
    {
        if ($this->cellNameIndexMap) {

            if (isset($this->cellNameIndexMap[$key]) === true) {
                $index = $this->cellNameIndexMap[$key];
                if (isset($this->_elements[$index]) === true) {
                    return ($this->_elements[$index]);
                }
            }
        } else {
            return $this->_elements[$key];
        }
    }

    protected function open()
    {
        if ($this->fileHandler === null) {

            $this->fileHandler = fopen($this->fileName, 'r');
            if ($this->fileHandler === false) {
                throw new Exception('Unable to create/open file "' . $this->fileName . '".');
            }

        }
        return true;
    }

    public function getEof()
    {
        $this->open();
        return  feof($this->fileHandler);
    }



}