<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 25/10/2018
 * Time: 14:26
 */

/** Same thing as itemmanager.php and usermanager.php */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$db = PDOConnection::getMysqlConnexion();
$manager = new UserManagerPDO($db);

$userSession = getSessionUser();

if(empty($userSession) || $userSession->getStatus() != 'admin')
{
    header('Location: index.php');
}


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
        'status'  => $_POST['status'],
        'pictureName' => $_FILES['picture']['name'],
        'pictureTmpName' => $_FILES['picture']['tmp_name'],
        ));

    $valid = true;
    if(!$user->isValid())
        $valid = false;

    if(!$user->superPassword($_POST['superPassword']))
        $valid = false;

    if($manager->ifExists($user))
    {
        $valid = false;
        $errorMessages[] = "User already exists";
    }


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
    <?php include('../includes/head.php')?>
</head>
<body>
<?php include('../includes/navbar.php');?>
<?php
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
<div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
    <div class="col-12 col-md-8 col-lg-6">
        <a href="usermanager.php" class="p-0 m-0 btn btn-link text-dark">
            <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">admin</a>
    </div>
</div>
<div class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6 mt-3 mb-3">
    <h1 class="display-3">Create a user</h1>
</div>
<div>
    <form class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6 mt-3 mb-3 " action="register.php" enctype="multipart/form-data" method="post">
        <input class="form-control mb-3" type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']?>" placeholder="Name">
        <input class="form-control mb-3" type="text" name="surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname']?>" placeholder="Surname">
        <input class="form-control mb-3" type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" placeholder="Email">
        <input class="form-control mb-3" type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password']?>" placeholder="Password">
        <input class="form-control mb-3" type="password" name="passwordConfirmed" value="<?php if(isset($_POST['passwordConfirmed'])) echo $_POST['passwordConfirmed']?>" placeholder="Confirmation password">
        <div class="form-group">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
                <option value="user" <?php if(isset($_POST['status']) && $_POST['status'] == "user" ) echo "selected"?>>User</option>
                <option value="matron" <?php if(isset($_POST['status']) && $_POST['status'] == "matron" ) echo "selected"?>>Matron</option>
                <option value="admin" <?php if(isset($_POST['status']) && $_POST['status'] == "admin" ) echo "selected"?>>Admin</option>
            </select>
        </div>
        <input class="form-control mb-3" type="text" name="address1" value="<?php if(isset($_POST['address1'])) echo $_POST['address1']?>" placeholder="First Address">
        <input class="form-control mb-3" type="text" name="address2" value="<?php if(isset($_POST['address2'])) echo $_POST['address2']?>" placeholder="Second Address">
        <input class="form-control mb-3" type="number" name="postalCode" value="<?php if(isset($_POST['postalCode'])) echo $_POST['postalCode']?>" placeholder="Postal Code">
        <input class="form-control mb-3" type="tel" name="cellNum" value="<?php if(isset($_POST['cellNum'])) echo $_POST['cellNum']?>" placeholder="Cell Num">
        <div class="form-group">
            <label>Image :</label>
            <input type="file" name="picture">
        </div>
        <input class="form-control mb-3" type="password" name="superPassword" value="<?php if(isset($_POST['superPassword'])) echo $_POST['superPassword']?>" placeholder="Super Password">
        <input class="btn btn-primary btn-block" type="submit" name="submit" value="Send">
    </form>
</div>
</body>

<?php include '../includes/footer.php'?>

<?php include('../includes/script.php')?>
</html>
