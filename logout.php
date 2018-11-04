<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 14:43
 */

session_start();

$_SESSION = array();
session_destroy();
header('Location: index.php');
