<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 05.12.19
 * Time: 11:02
 */

namespace vova07\base\helpers;


class Printer
{
    const FORMAT_A4_WIDTH_INCH = 8.267;
    const FORMAT_A4_HEIGHT_INCH = 11.693;
    const FORMAT_A5_WIDTH_INCH = 5.847;
    const FORMAT_A5_HEIGHT_INCH = 8.267;
    const FORMAT_A6_WIDTH_INCH = 4.133;
    const FORMAT_A6_HEIGHT_INCH = 5.847;

    const FORMAT_A4 = 'A4';
    const FORMAT_A5 = 'A5';
    const FORMAT_A6 = 'A6';

    //public cssPageSizeProperty($format, )

    public static function  getGeometry($format = self::FORMAT_A4, $isLandScape = false )
    {
            $width = constant("self::FORMAT_" . $format . "_WIDTH_INCH" );
            $height =constant("self::FORMAT_" . $format . "_HEIGHT_INCH" );
            if ($isLandScape){
                $sw = $height;
                $height = $width;
                $width = $sw;
            }
            return [$width,$height];
    }

}