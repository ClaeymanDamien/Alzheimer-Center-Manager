<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 24/10/2018
 * Time: 14:43
 */
/** A basic logout file */
session_start();

$_SESSION = array();
session_destroy();
header('Location: index.php');
