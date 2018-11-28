<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 12/11/2018
 * Time: 13:57
 */

/** Same thing as usermanager.php and itemmanager.php */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$db = PDOConnection::getMysqlConnexion();
$manager = new UserManagerPDO($db);

$userSession = getSessionUser();

if(empty($userSession) || !($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron'))
{
    header('Location: index.php');
}

$register = isset($_GET['register']) ? $_GET['register'] : NULL;

if(isset($_GET['added']))
{
    $messages[] = "Patient is added";
}

if(isset($_GET['nf']))
    $messages[] = "Patient is not find";

/** Add an item */
if(isset($_POST['addSubmit']))
{
    $patient = new Patient(array(
        'id' => $manager->getLastInsertId()+1,
        'fName' => $_POST['name'],
        'lName' => $_POST['surname'],
        'address1' => $_POST['address1'],
        'address2' => $_POST['address2'],
        'email' => $_POST['nextOfKind'],
        'postalCode' => $_POST['postalCode'],
        'password' => $_POST['password'],
        'passwordConfirmed' => $_POST['passwordConfirmed'],
        'pictureName' => $_FILES['picture']['name'],
        'pictureTmpName' => $_FILES['picture']['tmp_name'],
        'roomNo' => $_POST['roomNb'],
        'gradeClassification' => $_POST['gradeClassification']
    ));


    $valid = true;

    if(!$patient->isValidAdd())
        $valid = false;

    if(!$patient->checkSamePassword())
        $valid = false;

    if(!empty($patient->getPictureName()))
    {
        if(!$patient->addPicture())
            $valid = false;
    }

    if($foreignKey = $manager->getForeignKey($patient))
    {
            $patient->setNextOfkin($foreignKey);
    }
    else
    {
        $valid = false;
        $messages[] = "User not found";
    }

    if(!$userSession->superPassword($_POST['superPassword']))
    {
        $valid = false;
        $messages[] = "Super Password is invalid";
    }

    if($valid)
    {
        $manager->addPatient($patient);
        header('Location: patientmanager.php?register&added');
    }
    else
    {
        $errors = $patient->getErrors();
    }

    if(isset($errors))
    {
        if(in_array(Patient::NOT_SAME_PASSWORD,$errors)){
            $messages[] = "Is not the same password";
        }
        if(in_array(Patient::INVALID_F_NAME,$errors)){
            $messages[] = "First name is invalid";
        }
        if(in_array(Patient::INVALID_L_NAME,$errors)){
            $messages[] = "Last name is invalid";
        }
        if(in_array(Patient::INVALID_EXTENSION,$errors)){
            $messages[] = "Picture extension is invalid";
        }
        if(in_array(Patient::INVALID_PASSWORD,$errors)){
            $messages[] = "Invalid password";
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
if(isset($register))
{
    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="patientmanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">patient manager</a>
        </div>
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <form class="col-12 col-md-8 col-lg-6 mb-5" action="patientmanager.php?register" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name: </label>
                <input id="name" class="form-control mb-3" type="text" name="name" value="<?php
                if(isset($_POST['name']) && !empty($_POST['name']))
                    echo $_POST['name'];
                ?>" placeholder="Name">
            </div>

            <div class="form-group">
                <label for="surname">Surname: </label>
                <input id="surname" class="form-control mb-3" type="text" name="surname" value="<?php
                if(isset($_POST['surname']) && !empty($_POST['surname']))
                    echo $_POST['surname'];
                ?>" placeholder="Surname">
            </div>

            <div class="form-group">
                <label for="name">Room number: </label>
                <input class="form-control mb-3" type="text" name="roomNb" value="<?php
                if(isset($_POST['roomNb']))
                    echo $_POST['roomNb'];
                ?>" placeholder="Room number">
            </div>

            <div class="form-group">
                <label for="name">Password: </label>
                <input class="form-control mb-3" type="password" name="password" value="<?php
                if(isset($_POST['password']))
                    echo $_POST['password'];
                ?>" placeholder="Password">
            </div>

            <div class="form-group">
                <label for="name">Password confirmed: </label>
                <input  class="form-control mb-3" type="password" name="passwordConfirmed" value="<?php
                if(isset($_POST['passwordConfirmed']))
                    echo $_POST['passwordConfirmed'];
                ?>" placeholder="Confirmation password">
            </div>

            <div class="form-group">
                <label for="name">Address 1: </label>
                <input class="form-control mb-3" type="text" name="address1" value="<?php
                if(isset($_POST['address1']))
                    echo $_POST['address1'];
                ?>" placeholder="First Adress">
            </div>

            <div class="form-group">
                <label for="name">Address 2: </label>
                <input class="form-control mb-3" type="text" name="address2" value="<?php
                if(isset($_POST['address2']))
                    echo $_POST['address2'];
                ?>" placeholder="Second Address">
            </div>

            <div class="form-group">
                <label for="name">Postal code: </label>
                <input class="form-control mb-3" type="text" name="postalCode" value="<?php
                if(isset($_POST['postalCode']))
                    echo $_POST['postalCode'];
                ?>" placeholder="Postal Code">
            </div>

            <div class="form-group">
                <label for="name">Next of kin: </label>
                <input class="form-control mb-3" type="email" aria-placeholder="Email of the user" name="nextOfkin" value="<?php
                if(isset($_POST['nextOfkin']))
                    echo $_POST['nextOfkin'];
                ?>" placeholder="Email of the nextOfkin">
            </div>

            <div class="form-group">
                <label for="name">Grade classification: </label>
                <select  class="form-control mb-3" title="gradeClassification" name="gradeClassification">
                    <option <?php
                    if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "A")
                        echo "selected ";
                    ?> value="A">A</option>
                    <option <?php
                            if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "B")
                                echo "selected ";
                            ?>value="B">B</option>
                    <option <?php
                    if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "C")
                        echo "selected ";
                    ?> value="C">C</option>
                </select>
            </div>
            <div class="form-group d-flex flex-column">
                <label>Patient image :</label>
                <input class="mb-3" type="file" name="picture">
            </div>

            <div class="form-group">
                <label for="name">Super password: </label>
                <input class="form-control mb-3" type="password" name="superPassword" value="<?php
                if(isset($_POST['superPassword']))
                    echo $_POST['superPassword'];
                ?>" placeholder="Super Password">
            </div>
            <input class="btn btn-primary btn-block mb-3" type="submit" name="addSubmit" value="Send">
        </form>
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
            <h1 class="display-3 p-3">Patient Manager</h1>
            <div class="d-flex justify-content-between">
                <div class="col-6 p-3">
                    <a href="patientmanager.php?register" class="btn btn-block btn-primary">Add</a>
                </div>
                <div class="col-6 p-3">
                    <a href="patientedit.php" class="btn btn-block btn-primary">List</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php include '../includes/footer.php'?>

<?php include "../includes/script.php";?>
</body>
</html>
