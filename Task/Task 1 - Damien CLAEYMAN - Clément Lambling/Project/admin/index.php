<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 15:23
 */
session_start();
include('../lib/autoload.php');
include('../lib/tools.php');

$userSession = getSessionUser();

if($userSession != NULL)
{
    if(!($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron'))
        header('Location: ../index.php');
}
else
{
    header('Location: ../index.php');
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('../includes/head.php')?>
</head>
<body>
   <?php include('../includes/navbar.php') ?>
    <h1 class="text-center pt-5 display-3">Admin</h1>
    <div class="d-flex justify-content-center align-items-center flex-column flex-md-row pt-5">
        <?php
    if($userSession->getStatus() == 'admin')
    {
        ?>
            <a href="register.php" class="btn btn-primary col-10 col-md-6 col-lg-3 m-2">Add new user</a>
        <?php
    }
    if($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron')
    {
        ?>
        
            <a href="patientedit.php" class="btn btn-primary col-10 col-md-6 col-lg-3 m-2">Edit patient</a>
       
    
        <?php
    }
    ?>
    </div>
    

    <?php include('../includes/script.php')?>
</body>
</html>

