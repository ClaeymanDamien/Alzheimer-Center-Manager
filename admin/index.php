<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 24/10/2018
 * Time: 15:23
 */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$userSession = getSessionUser();

//Security, check if connected and if it's admin or matron
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
    <div class="container-fluid row pt-5">
        <?php
        //admin option if it's an admin or matron
        if($userSession->getStatus() == 'admin')
        {
            ?>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="usermanager.php" class="btn p-md-4 p-3 btn-block btn-primary">User manager</a>
            </div>
            <?php
        }
        if($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron') //admin and matron option
        {
            ?>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="patientmanager.php" class="btn p-md-4 p-3 btn-block btn-primary">Patient manager</a>
            </div>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="itemmanager.php" class="btn p-md-4 p-3 btn-block btn-primary">Item manager</a>
            </div>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="medicinemanager.php" class="btn p-md-4 p-3 btn-block btn-primary">Medicine manager</a>
            </div>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="itempatient.php" class="btn p-md-4 p-3 btn-block btn-primary">Item to patient</a>
            </div>
            <div class="col-12 col-md-6 col-lg-4 p-3">
                <a href="medicinepatient.php" class="btn p-md-4 p-3 btn-block btn-primary">Medicine to patient</a>
            </div>
            <?php
        }
        ?>
    </div>

    <?php include '../includes/footer.php'?>

    <?php include('../includes/script.php')?>
</body>
</html>

