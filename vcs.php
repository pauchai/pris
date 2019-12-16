<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 12/16/19
 * Time: 8:52 AM
 */
$files = glob('vendor/*/*/.git',GLOB_ONLYDIR);
array_unshift($files, './.git');
$baseDir  = `pwd`;
foreach ($files as $file)
{
    $file = dirname($file);
    $fullFile  = realpath($file);

    $res =   `cd $fullFile; git status -s`;
    if ($res){
        echo $file . "\n";
        //echo $res;
    }

}