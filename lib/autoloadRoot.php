<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 25/10/2018
 * Time: 15:34
 */

function autoload($classname)
{
    if (file_exists($file ='class/'. $classname . '.php'))
    {
        require $file;
    }
}

spl_autoload_register('autoload');