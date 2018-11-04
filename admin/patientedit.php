<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 26/10/2018
 * Time: 12:27
 */

session_start();
include('../lib/autoload.php');
include('../lib/tools.php');

$db = PDOFactory::getMysqlConnexion();
$manager = new UserManagerPDO($db);

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

$edit = isset($_GET['edit']) ? $_GET['edit'] : NULL;
$patient = isset($_GET['patient']) ? $_GET['patient'] : NULL;

if($edit)
{
    $reqPatient = $manager->selectPatient($edit);
}
else
{
    $reqPatient = $manager->selectPatient($patient);
}

$patientEditInfo = $reqPatient->fetch();


if(isset($_POST['submit']) && isset($patientEditInfo))
{
    $patient = new Patient(array(
        'id' => $edit,
        'fName' => empty($_POST['name']) ? $patientEditInfo['FName'] : $_POST['name'],
        'lName' => empty($_POST['surname']) ? $patientEditInfo['LName'] : $_POST['surname'],
        'address1' => empty($_POST['Address1']) ? $patientEditInfo['Address1'] : $_POST['Address1'],
        'address2' => empty($_POST['Address2']) ? $patientEditInfo['Address2'] : $_POST['Address2'],
        'postalCode' => empty($_POST['PostalCode']) ? $patientEditInfo['PostalCode'] : $_POST['PostalCode'],
        'password' => $_POST['password'],
        'passwordConfirmed' => $_POST['passwordConfirmed'],
        'pictureName' => $_FILES['picture']['name'],
        'pictureTmpName' => $_FILES['picture']['tmp_name'],
        'roomNo' => empty($_POST['roomNb']) ? $patientEditInfo['RoomNb'] : $_POST['roomNb'],
        'gradeClassification' => empty($_POST['gradeClassification']) ? $patientEditInfo['GradeClassification'] : $_POST['gradeClassification']
    ));

    $valid = true;
    if(!$patient->isValid())
    {
        $valid = false;
    }
    if(!empty($patient->getPassword()))
    {
        if(!$patient->checkSamePassword())
            $valid = false;
    }

    if(!empty($patient->getPictureTmpName()))
    {
        if(!$patient->addPicture())
            $valid = false;
    }

    if(!$patient->superPassword($_POST['superPassword']))
    {
        $valid = false;
    }

    if($valid)
    {
        $manager->updatePatient($patient);
        $errorMessages[] = "Update was done";
    }
    else
    {
        $errors = $patient->getErrors();
    }

    if(isset($errors))
    {
        if(in_array(User::NOT_SAME_PASSWORD,$errors)){
            $errorMessages[] = "Is not the same password";
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

if(isset($edit) && !empty($edit))
{
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
           <a href="patientedit.php" class="p-0 m-0 btn btn-link text-dark">
        <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">patient list</a>
       </div>
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <form class="col-12 col-md-8 col-lg-6 mb-5" action="patientedit.php?edit=<?php echo $edit ?>" method="post" enctype="multipart/form-data">
           <div class="form-group">
               <label for="name">Name: </label>
               <input id="name" class="form-control mb-3" type="text" name="name" value="<?php
                if(isset($_POST['name']) && !empty($_POST['name']))
                    echo $_POST['name'];
                else if(isset($patientEditInfo))
                    echo $patientEditInfo['FName'];
                ?>" placeholder="Name">
           </div>
            
            <div class="form-group">
                <label for="surname">Surname: </label>
                <input id="surname" class="form-control mb-3" type="text" name="surname" value="<?php
                if(isset($_POST['surname']) && !empty($_POST['surname']))
                    echo $_POST['surname'];
                elseif(isset($patientEditInfo))
                    echo $patientEditInfo['LName'];
                ?>" placeholder="Surname">
            </div>
            
            <div class="form-group">
                <label for="name">Room number: </label>
                <input class="form-control mb-3" type="text" name="roomNb" value="<?php
                if(isset($_POST['roomNb']))
                    echo $_POST['roomNb'];
                elseif(isset($patientEditInfo))
                    echo $patientEditInfo['RoomNb'];
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
                if(isset($_POST['Address1']))
                    echo $_POST['Address1'];
                elseif(isset($patientEditInfo))
                    echo $patientEditInfo['Address1'];
                ?>" placeholder="First Adress">
            </div>

           <div class="form-group">
               <label for="name">Address 2: </label>
                <input class="form-control mb-3" type="text" name="address2" value="<?php
                if(isset($_POST['Address2']))
                    echo $_POST['Address2'];
                elseif(isset($patientEditInfo))
                    echo $patientEditInfo['Address2'];
                ?>" placeholder="Second Address">
            </div>

           <div class="form-group">
                <label for="name">Postal code: </label>
                <input class="form-control mb-3" type="text" name="postalCode" value="<?php
                if(isset($_POST['PostalCode']))
                    echo $_POST['PostalCode'];
                elseif(isset($patientEditInfo))
                    echo $patientEditInfo['PostalCode'];
                ?>" placeholder="Postal Code">
            </div>
            
            <div class="form-group">
                <label for="name">Next of kind: </label>
                <input class="form-control mb-3" type="email" name="nextOfKind" value="" placeholder="NextOfKind">
            </div>
            
            <div class="form-group">
                <label for="name">Grade classification: </label>
                <select  class="form-control mb-3" title="gradeClassification" name="gradeClassification">
                    <option <?php
                        if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "A")
                            echo "selected ";
                        elseif(isset($patientEditInfo) && $patientEditInfo['GradeClassification'] == "A")
                            echo "selected ";
                        ?> value="A">A</option>
                    <option <?php
                        if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "B")
                            echo "selected ";
                        elseif(isset($patientEditInfo) && $patientEditInfo['GradeClassification'] == "B")
                            echo "selected ";
                        ?>value="B">B</option>
                    <option <?php
                    if(isset($_POST['gradeClassification']) && $_POST['gradeClassification'] == "C")
                        echo "selected ";
                    elseif(isset($patientEditInfo) && $patientEditInfo['GradeClassification'] == "C")
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
            
            <input class="btn btn-primary btn-block mb-3" type="submit" name="submit" value="Send">
        </form>
    </div>

    <?php
}
elseif(isset($patient) && !empty($patient))
{
    ?>
    <div class="container-fluid table-responsive">
        <h2 class="mt-3">Patient information</h2>
        <table class="mt-5 table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Room number</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>Postal code</th>
                <th>Grade classification</th>
                <th>User ID</th>
                <th>Patient image</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $patientEditInfo['ID'] ?></td>
                <td><?php echo $patientEditInfo['FName'] ?></td>
                <td><?php echo $patientEditInfo['LName'] ?></td>
                <td><?php echo $patientEditInfo['RoomNb'] ?></td>
                <td><?php echo $patientEditInfo['Address1'] ?></td>
                <td><?php echo $patientEditInfo['Address2'] ?></td>
                <td><?php echo $patientEditInfo['PostalCode'] ?></td>
                <td><?php echo $patientEditInfo['GradeClassification'] ?></td>
                <td><?php echo $patientEditInfo['NextOfKin'] ?></td>
                <td>
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#showFace">
                        Show face
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex mt-3 justify-content-center">
        <div class="pr-3 col-6 col-md-3 col-lg-2">
            <a class="btn btn-primary btn-block" href="patientedit.php">Patient list</a>
        </div>
        <div class="pl-3 col-6 col-md-3 col-lg-2">
            <a class="btn btn-primary btn-block" href="patientedit.php?edit=<?php echo $patientEditInfo['ID']?>">Edit</a>
        </div>
    </div>
    <div class="modal fade" id="showFace">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Patient picture</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                        <?php
                        $filename = '../'.$patientEditInfo['PatientImage'];
                        if(file_exists($filename))
                        {
                            ?>
                            <img src="../<?php echo $patientEditInfo['PatientImage']?>" alt="patient" class="card-img-top">
                            <?php
                        }
                        else
                        {
                            ?>
                            <img src="../images/styles/profile.jpg" alt="user" class="card-img-top">
                            <?php
                        }
                        ?>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
else
{
    ?>
    <div class="d-flex justify-content-center align-items-center flex-column">
    <div class="col-12 col-md-8 col-6">
        <h1 class="display-3 mb-3"> Patient list</h1>
        <input class="form-control mb-4" id="myInput" type="text" placeholder="Search..">
        <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="myTable">
            <?php
            $allPatients = $manager->selectAllPatient();
            while($patientInfo = $allPatients->fetch())
            {
                ?>

              <tr>
                <td><?php echo $patientInfo['FName'] ?></td>
                <td><?php echo $patientInfo['LName'] ?></td>
                <td class="d-flex justify-content-around">
                    <a href="patientedit.php?patient=<?php echo $patientInfo['ID']?>">Patient information </a>
                    <a href="patientedit.php?edit=<?php echo $patientInfo['ID']?>">Modifier</a>
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
?>

<?php include('../includes/script.php')?>
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
