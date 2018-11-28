<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 25/10/2018
 * Time: 14:26
 */

session_start();

require_once(__DIR__ . '/lib/utilities.php');

/** connection to the database */
$db = PDOConnection::getMysqlConnexion();
$manager = new UserManagerPDO($db);

$userSession = getSessionUser();

/** redirection */
if(!empty($userSession))
{
    header('Location: index.php');
}

/** create a new object with information sended by the form */
if(isset($_POST['submit']))
{

    $user = new User(array(
        'id' => $manager->getLastInsertId()+1,
        'fName' => $_POST['name'],
        'lName' => $_POST['surname'],
        'address1' => $_POST['address1'],
        'address2' => $_POST['address2'],
        'postalCode' => $_POST['postalCode'],
        'email'  => $_POST['email'],
        'cellNum' => $_POST['cellNum'],
        'password' => $_POST['password'],
        'passwordConfirmed' => $_POST['passwordConfirmed'],
        'status'  => 'user',
        'pictureName' => $_FILES['picture']['name'],
        'pictureTmpName' => $_FILES['picture']['tmp_name'],
    ));

        /** check if information are valid */
    $valid = true;
    if(!$user->isValid())
        $valid = false;

    if($manager->ifExists($user))
    {
        $valid = false;
        $errorMessages[] = "User already exists";
    }

    /** check is information are valid, if there is picture, if yes we add it */
    if($valid)
    {
        if(!empty($user->getPictureName()))
        {
            $user->addPicture();
        }
        $manager->register($user);
        $_POST = array();
        $errorMessages[] = "User added";
    }
    else
    {
        $errors = $user->getErrors();
    }


    /** create a error message */
    if(isset($errors))
    {
        if(in_array(User::NOT_SAME_PASSWORD,$errors)){
            $errorMessages[] = "Is not the same password";
        }
        if(in_array(User::INVALID_EMAIL,$errors)){
            $errorMessages[] = "Email is invalid";
        }
        if(in_array(User::INVALID_F_NAME,$errors)){
            $errorMessages[] = "First name is invalid";
        }
        if(in_array(User::INVALID_L_NAME,$errors)){
            $errorMessages[] = "Last name is invalid";
        }
        if(in_array(User::INVALID_EXTENSION,$errors)){
            $errorMessages[] = "Picture extension is invalid";
        }
        if(in_array(User::INVALID_SUPER_PASSWORD,$errors)){
            $errorMessages[] = "Super password is invalid";
        }
        if(in_array(User::INVALID_PASSWORD,$errors)){
            $errorMessages[] = "Password is invalid";
        }

    }


}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php')?>
</head>
<body>
<?php include('includes/navbar.php');?>

<div class="container col-12 col-md-9 col-lg-6 mt-5 mb-5">
    <?php
    /** display the error message */
    if(isset($errorMessages))
    {
        foreach($errorMessages as $errorMessage)
        {
            ?>
            <div class="alert alert-primary alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo "<div>".$errorMessage."</div>"; ?>
            </div>
            <?php
        }
    }
    ?>
    <div class="modal-content p-2">
        <!-- Header Form -->
        <div class="modal-header">
            <h2 class="modal-title text-secondary">Sign up</h2>
        </div>
        <!-- Body form-->
        <div class="modal-body">
            <form action="signup.php" enctype="multipart/form-data" method="post">
                <input class="form-control mb-3" type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']?>" placeholder="Name">
                <input class="form-control mb-3" type="text" name="surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname']?>" placeholder="Surname">
                <input class="form-control mb-3" type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" placeholder="Email">
                <input class="form-control mb-3" type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']?>" placeholder="Password">
                <input class="form-control mb-3" type="password" name="passwordConfirmed" value="<?php if(isset($_POST['passwordConfirmed'])) echo $_POST['passwordConfirmed']?>" placeholder="Confirmation password">
                <input class="form-control mb-3" type="text" name="address1" value="<?php if(isset($_POST['address1'])) echo $_POST['address1']?>" placeholder="First Address">
                <input class="form-control mb-3" type="text" name="address2" value="<?php if(isset($_POST['address2'])) echo $_POST['address2']?>" placeholder="Second Address">
                <input class="form-control mb-3" type="number" name="postalCode" value="<?php if(isset($_POST['postalCode'])) echo $_POST['postalCode']?>" placeholder="Postal Code">
                <input class="form-control mb-3" type="tel" name="cellNum" value="<?php if(isset($_POST['cellNum'])) echo $_POST['cellNum']?>" placeholder="Cell Num">
                <div class="form-group">
                    <label>Image :</label>
                    <input type="file" name="picture">
                </div>
                <input class="btn btn-dark btn-lg btn-block" type="submit" name="submit" value="Send">
            </form>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <span>You are registered ?
                <a href="login.php" class="text-info">Log in</a>
            </span>
        </div>
    </div>
</div>
</body>

<?php include 'includes/footer.php'?>


<?php include('includes/script.php')?>
</html>
