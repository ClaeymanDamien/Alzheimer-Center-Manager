<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 06/10/2018
 * Time: 11:33
 */

function autoloadRoot($classname)
{
    if (file_exists($file ='../class/'. $classname . '.php'))
    {
        require $file;
    }
}

spl_autoload_register('autoloadRoot');
