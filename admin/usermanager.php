<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:59
 */

/** Same thing as itemmanager.php */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$db = PDOConnection::getMysqlConnexion();
$manager = new UserManagerPDO($db);
$managerLinkTable = new LinkTableManagerPDO($db);

$userSession = getSessionUser();

if(empty($userSession) || $userSession->getStatus() != 'admin')
{
    header('Location: index.php');
}

$delete = isset($_GET['delete']) ? $_GET['delete'] : NULL;
$edit = isset($_GET['edit']) ? $_GET['edit'] : NULL;
$userList = isset($_GET['userList']) ? $_GET['userList'] : NULL;


if(isset($_GET['deleted']))
{
    $messages[] = "User is deleted";
}
if(isset($_GET['added']))
{
    $messages[] = "User is added";
}

if(isset($_GET['errors']))
{
    $errorMessage = $_GET['errors'];

    $messages[] = ($errorMessage == "np") ? "Patient is not find" : NULL;
}


/** Delete an User */
if(isset($_POST['deleteSubmit']) && isset($delete))
{
    $User = new User();

    //if superpassword is right
    if($User->superPassword($_POST['superPassword']))
    {
        //request next of kin
        $reqPatient = $manager->selectForeignPatient($delete);

        //delete all next of kin
        while ($patient = $reqPatient->fetch())
        {
            $managerLinkTable->deletePatient($patient['ID']);
            $manager->deletePatient($patient['ID']);
        }

        $manager->deleteUser($delete);
        header('Location: usermanager.php?deleted');
    }
    else
    {
        $errors = $User->getErrors();
    }

    if(isset($errors))
    {
        if(in_array(User::INVALID_SUPER_PASSWORD,$errors)){
            $messages[] = "Super password is invalid";
        }
    }
}

/** Edit an User */
if(isset($edit))
{

    $UserInfoReq = $manager->selectUser($edit);
    $UserInfo = $UserInfoReq->fetch();

    if($UserInfo <= 0)
    {
        header('Location: usermanager.php');
    }

    if(isset($_POST['editSubmit']))
    {
       $User = new User(array(
        'id' => $edit,
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
        $password = false;
        if(!$User->isValidUpdate())
        {
            $valid = false;
        }

        if(!empty($User->getPassword()))
        {
            $password = true;
            if(!$User->checkSamePassword())
                $valid = false;
        }

        if(!empty($User->getPictureTmpName()))
        {
            if(!$User->addPicture())
                $valid = false;
        }

        if(!$User->superPassword($_POST['superPassword']))
        {
            $valid = false;
        }

        if($valid)
        {
            $manager->updateUser($User);
            $messages[] = "Update was done";
        }
        else
        {
            $errors = $User->getErrors();
        }

        if(isset($errors))
        {
            if(in_array(User::NOT_SAME_PASSWORD,$errors)){
                $messages[] = "Is not the same password";
            }
            if(in_array(User::INVALID_EMAIL,$errors)){
                $messages[] = "Email is invalid";
            }
            if(in_array(User::INVALID_F_NAME,$errors)){
                $messages[] = "First name is invalid";
            }
            if(in_array(User::INVALID_L_NAME,$errors)){
                $messages[] = "Last name is invalid";
            }
            if(in_array(User::INVALID_EXTENSION,$errors)){
                $messages[] = "Picture extension is invalid";
            }
            if(in_array(User::INVALID_SUPER_PASSWORD,$errors)){
                $messages[] = "Super password is invalid";
            }
            if($password)
            {
                if(in_array(User::INVALID_PASSWORD,$errors)){
                    $messages[] = "Password is invalid";
                }
            }
        }
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <?php include "../includes/head.php";?>
</head>
<body>
<?php include "../includes/navbar.php";?>

<?php
if(isset($messages))
{
    foreach($messages as $message)
    {
        ?>
        <div class="alert alert-primary alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo "<div>".$message."</div>"; ?>
        </div>
        <?php
    }
}
?>

<?php

if (isset($delete))
{
    ?>
    <div class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6 border mt-5 p-3">
        <h4 class="p-3">Are you sure you want to delete this User?</h4>
        <form action="usermanager.php?delete=<?php echo $delete?>" method="post">
            <input class="form-control mb-3" type="password" name="superPassword" value="<?php
            if(isset($_POST['superPassword']))
                echo $_POST['superPassword'];
            ?>" placeholder="Super Password">

            <div class="row">
                <div class="col-6 pl-3 pr-3">
                    <input type="submit" name="deleteSubmit" class="btn btn-block btn-outline-primary" value="Yes">
                </div>
                <div class="col-6 pl-3 pr-3">
                    <a href="usermanager.php?userList" class="btn btn-block btn-primary">No</a>
                </div>
            </div>
        </form>
    </div>
    <?php
}
elseif (isset($edit))
{
    $req = $manager->selectUser($edit);
    $userInfo = $req->fetch();

    if($userInfo <= 0)
        header('Location: usermanager.php?errors=np');
    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="usermanager.php?userList" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">list users</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3">Edit a new User</h1>
            <form class="mt-3 mb-3 " action="usermanager.php?edit=<?php echo $edit?>" enctype="multipart/form-data" method="post">
                <input class="form-control mb-3" type="text" name="name" value="<?php
                if(isset($_POST['name']))
                    echo $_POST['name'];
                elseif (isset($userInfo['FName']))
                    echo $userInfo['FName'];
                ?>" placeholder="Name">
                <input class="form-control mb-3" type="text" name="surname" value="<?php
                if(isset($_POST['surname']))
                    echo $_POST['surname'];
                elseif (isset($userInfo['LName']))
                    echo $userInfo['LName'];
                ?>" placeholder="Surname">
                <input class="form-control mb-3" type="email" name="email" value="<?php
                if(isset($_POST['email']))
                    echo $_POST['email'];
                elseif (isset($userInfo['Email']))
                    echo $userInfo['Email'];
                ?>" placeholder="Email">
                <input class="form-control mb-3" type="password" name="password" value="<?php
                if(isset($_POST['password']))
                    echo $_POST['password'];
                ?>" placeholder="Password">
                <input class="form-control mb-3" type="password" name="passwordConfirmed" value="<?php
                if(isset($_POST['passwordConfirmed']))
                    echo $_POST['passwordConfirmed'];
                ?>" placeholder="Confirmation password">
                <div class="form-group">
                    <label for="status">Status :</label>
                    <select class="form-control" name="status" id="status">
                        <option value="user" <?php
                        if(isset($_POST['status']) && $_POST['status'] == "user" )
                            echo "selected";
                        elseif (isset($userInfo['Status']) && $userInfo['Status'] == "user")
                            echo "selected";
                        ?>>User</option>
                        <option value="matron" <?php
                        if(isset($_POST['status']) && $_POST['status'] == "matron" )
                            echo "selected";
                        elseif (isset($userInfo['Status']) && $userInfo['Status'] == "matron")
                            echo "selected";
                        ?>>Matron</option>
                        <option value="admin" <?php
                        if(isset($_POST['status']) && $_POST['status'] == "admin" )
                            echo "selected";
                        elseif (isset($userInfo['Status']) && $userInfo['Status'] == "admin")
                            echo "selected";
                        ?>>Admin</option>
                    </select>
                </div>
                <input class="form-control mb-3" type="text" name="address1" value="<?php
                if(isset($_POST['address1']))
                    echo $_POST['address1'];
                elseif (isset($userInfo['Address1']))
                    echo $userInfo['Address1'];
                ?>" placeholder="First Address">
                <input class="form-control mb-3" type="text" name="address2" value="<?php
                if(isset($_POST['address2']))
                    echo $_POST['address2'];
                elseif (isset($userInfo['Address2']))
                    echo $userInfo['Address2'];
                ?>" placeholder="Second Address">
                <input class="form-control mb-3" type="number" name="postalCode" value="<?php
                if(isset($_POST['postalCode']))
                    echo $_POST['postalCode'];
                elseif (isset($userInfo['PostalCode']))
                    echo $userInfo['PostalCode'];
                ?>" placeholder="Postal Code">
                <input class="form-control mb-3" type="tel" name="cellNum" value="<?php
                if(isset($_POST['cellNum']))
                    echo $_POST['cellNum'];
                elseif (isset($userInfo['CellNum']))
                    echo $userInfo['CellNum'];
                ?>" placeholder="Cell Num">

                <div class="form-group">
                    <label for="picture">Picture: </label>
                    <input type="file" id="picture" class="form-control-file" name="picture" placeholder=""
                           aria-describedby="fileHelpId">
                </div>

                <input class="form-control mb-3" type="password" name="superPassword" value="<?php
                if(isset($_POST['superPassword']))
                    echo $_POST['superPassword'];
                ?>" placeholder="Super Password">
                <input class="btn btn-primary btn-block" type="submit" name="editSubmit" value="Send">
            </form>
        </div>
    </div>
    <?php
}
elseif (isset($userList))
{

    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="usermanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">User manager</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-3">Users list</h1>
            <input class="form-control mb-4" id="myInput" type="text" placeholder="Search..">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="myTable">
                <?php
                $allUsers = $manager->selectAllUser();
                while($UserInfo = $allUsers->fetch())
                {
                    ?>

                    <tr>
                        <td><?php echo $UserInfo['FName'] ?></td>
                        <td><?php echo $UserInfo['LName'] ?></td>
                        <td class="d-flex justify-content-around">
                            <a href="usermanager.php?edit=<?php echo $UserInfo['ID']?>">Edit</a>
                            <a href="usermanager.php?delete=<?php echo $UserInfo['ID']?>">Delete</a>
                        </td>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
else
{
    ?>
    <div class="container-fluid p-4">
        <div class="pl-4 col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <a href="index.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">admin</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3 p-3">User Manager</h1>
            <div class="d-flex justify-content-between">
                <div class="col-6 p-3">
                    <a href="register.php" class="btn btn-block btn-primary">Add</a>
                </div>
                <div class="col-6 p-3">
                    <a href="usermanager.php?userList" class="btn btn-block btn-primary">List</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php include '../includes/footer.php'?>

<?php include "../includes/script.php";?>
<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
</body>
</html>
