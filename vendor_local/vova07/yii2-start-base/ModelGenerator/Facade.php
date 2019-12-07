<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/17/19
 * Time: 8:57 AM
 */

namespace vova07\base\ModelGenerator;

/**
 * Class Facade
 * @package vova07\base\ModelGenerator
 *
 *
 */

class Facade
{

    /**
     * @var ModelPropagator
     */
    public static $propagator;
    /**
     * @var Dispenser
     */
    public static $dispenser;

    /**
     * Facade:store($model,)
     * @param $model
     * @param $doValidate
     * @param $extraFields
     * @return bool
     */
    public static function store($model, $doValidate=false,$extraFields=[])
    {
        if (!self::$propagator){
            self::$propagator = new ModelPropagator();
        }
       return self::$propagator->save($model,$doValidate, $extraFields);
    }


    public static function generateTable($model)
    {

    }

    public static function dispense($model)
    {
        if (!self::$dispenser){
            self::$dispenser = new Dispenser();
        }
        return self::$dispenser->run($model);
    }



}
