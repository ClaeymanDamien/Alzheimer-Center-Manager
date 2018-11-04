<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 11:43
 */
session_start();
include('lib/autoloadRoot.php');
include('lib/tools.php');

$userSession = getSessionUser();



?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php')?>
</head>
<body>
<?php include('includes/navbar.php')?>
<?php
if(isset($_GET['logged']) && !empty($userSession))
{
    ?>
    <div class="alert alert-primary alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo "User ". $userSession->getFName()." ". $userSession->getLName() ." is logged in and it's a(n) ".$userSession->getStatus(); ?>
    </div>

    <?php
}
?>
<div class="container-fluid p-0 m-0 height pt-5">
    <div class="col-12 d-flex flex-column justify-content-center align-items-center pt-5">
        <h1 class="text-center col-12 col-md-8 col-lg-6 display-3 pb-5" >Welcome to the Alzheimer Center Manager</h1>
        <?php
            if(empty($userSession))
            {
                ?>
                <div class="col-12 col-md-8 col-lg-6 p-0 m-0 pt-5">
                    <a href="login.php" class="btn btn-outline-primary btn-block">Login</a>
                </div>
                <?php
            }
            elseif($userSession->getStatus() == "admin" || $userSession->getStatus() == "matron")
            {
                ?>
                <div class="col-12 col-md-8 col-lg-6 p-0 m-0 d-flex justify-content-between">
                    <div class="col-6 mr-2 p-0 m-0 pt-5">
                        <a href="admin" class="btn btn-outline-primary btn-block">Admin</a>
                    </div>
                    <div class="col-6 ml-2 p-0 m-0 pt-5">
                        <a href="profile.php" class="btn btn-primary btn-block">Profile</a>
                    </div>
                </div>
                <?php
            }
            else{
                ?>
                <div class="col-12 col-md-8 col-lg-6 p-0 m-0 pt-5">
                    <a href="profile.php" class="btn btn-primary btn-block">Profile</a>
                </div>
            <?php
            }
        ?>

    </div>
</div>



<?php include('includes/script.php')?>
</body>
</html>
