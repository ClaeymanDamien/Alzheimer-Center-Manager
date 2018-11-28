<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 28/11/2018
 * Time: 12:51
 */

require_once "../lib/utilities.php";

$db = PDOConnection::getMysqlConnexion();

$file = file_get_contents("mydb.sql");

$query = $db->prepare($file);

if ($query->execute())
    header("Location: " . $_SERVER["HTTP_REFERER"] . "?dbloaded");
else
    header("Location: " . $_SERVER["HTTP_REFERER"] . "?dbfailed");
