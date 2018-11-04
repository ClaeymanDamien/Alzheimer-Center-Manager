<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 19:52
 */

/**
 * @return mixed|null
 */

function getSessionUser()
{
    if(empty($_SESSION['user']))
    {
        return NULL;
    }
    else
    {
        return unserialize($_SESSION['user']);
    }
}

function getRoot()
{
    $folder = "/project/";
    return "http://".$_SERVER['HTTP_HOST'].$folder;
}


