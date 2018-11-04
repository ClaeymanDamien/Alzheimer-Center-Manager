<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 11:46
 */
session_start();
require 'lib/autoloadRoot.php';
include('lib/tools.php');

$userSession = getSessionUser();

if(!empty($userSession))
{
    header('Location: index.php');
}

$db = PDOFactory::getMysqlConnexion();
$manager = new UserManagerPDO($db);

if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);


    $user = $manager->login($name, $surname, $email,$password);

    if($user)
    {
        $_SESSION['user'] = serialize($user);
        header('Location: index.php?logged');
    }
    else
    {
        $alertMessage = 'Name, surname, email or password invalid';
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php')?>
</head>
<body>
<?php include('includes/navbar.php')?>
<div class="container col-12 col-md-9 col-lg-6 mt-5 mb-5">
    <?php
    if(isset($alertMessage))
    {
        ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $alertMessage; ?>
        </div>
        <?php
    }
    ?>
    <div class="modal-content p-2">
        <!-- Header Form -->
        <div class="modal-header">
            <h2 class="modal-title text-secondary">Log in</h2>
        </div>
        <!-- Body form-->
        <div class="modal-body">
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="text" name="name" class="form-control pl-5 name_icon"  value="<?php if(isset($_POST['name'])) echo $_POST['name'] ?>" placeholder="Name">
                </div>
                <div class="form-group">
                    <input type="text" name="surname" class="form-control pl-5 name_icon"  value="<?php if(isset($_POST['surname'])) echo $_POST['surname'] ?>" placeholder="Surname">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control pl-5 mail_icon" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control pl-5 password_icon" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" placeholder="Password">
                </div>
                <button type="submit" name="submit" class="btn btn-dark btn-lg btn-block">Log in</button>
            </form>
        </div>

        <!-- form footer -->
        <div class="modal-footer d-flex justify-content-center">
            <span>You aren't registered yet?
                <a href="#" class="text-info">Sign up</a>
            </span>
        </div>
    </div>
</div>

<?php include('includes/script.php')?>
</body>
</html>
