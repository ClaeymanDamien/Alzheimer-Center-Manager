<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
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


/** change the name of folder with the one where you are  */

/** get the root path */
function getRoot()
{
    $folder = "/project/";
    return "http://".$_SERVER['HTTP_HOST'].$folder;
}

/** function to send a mail */
function  mailNotification(Item $Item, Patient $Patient, User $User)
{
    $recipient = $User->getEmail();
    $subject = "Alzheimer Center Manager: It will be necessary to add items";
    $header = "To: cputefrei@gmail.com";
    $message = "Hello ".$User->getFName()." ".$User->getLName()."
        
        There are only ".$Item->getQuantity()." ".$Item->getItemDesc()." left for the patient ".$Patient->getFName()." ".$Patient->getLName()."
        
        Best Regards
        Alzheimer Center Manager
        
        ---------------------------------
        
        This is automatically generated mail, thank you not to answer it.";

    mail($recipient, $subject, $message, $header) ;
}

/** load every class */
function autoload($classname)
{

    if (file_exists($file = dirname(__DIR__).'/class/'. $classname . '.php'))
    {
        require $file;
    }
}

spl_autoload_register('autoload');



