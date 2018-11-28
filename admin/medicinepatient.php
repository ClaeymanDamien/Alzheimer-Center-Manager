<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 07/11/2018
 * Time: 13:57
 */


/** Same thing as itempatient.php */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$db = PDOConnection::getMysqlConnexion();
$managerMedicine = new MedicineManagerPDO($db);
$managerPatient = new UserManagerPDO($db);
$managerTable = new LinkTableManagerPDO($db);

$userSession = getSessionUser();

if(empty($userSession) || !($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron'))
{
    header('Location: index.php');
}

$id = isset($_GET['id']) ? $_GET['id'] : NULL;

if(isset($_GET['errors']))
{
    $errorMessage = $_GET['errors'];

    $messages[] = ($errorMessage == "np") ? "Patient is not find" : NULL;
}

/** Register, Update, Delete*/
if(isset($id))
{
    $reqPatient = $managerPatient->selectPatient($id);
    $patientInfo = $reqPatient->fetch();

    if($patientInfo<=0 || empty($id))
    {
        header('Location: medicinepatient.php?errors=np');
    }

    if(isset($_POST['addSubmit']))
    {
        $medicine = new medicine(array(
           'dosage' => $_POST['dosage'],
            'id' => $_POST['id']
        ));

        $Patient = new Patient(array(
           'id' => $id
        ));

        if($medicine->isValidAddPatient())
        {
            $managerTable->addMedicinePatient($Patient, $medicine);
        }
    }

    if(isset($_POST['updateSubmit']))
    {
        $medicine = new medicine(array(
            'dosage' => $_POST['dosage'],
            'id' => $_POST['id']
        ));

        $Patient = new Patient(array(
            'id' => $id
        ));

        if($medicine->isValidAddPatient())
        {
            $managerTable->updateMedicinePatient($Patient, $medicine);
        }
    }

    if(isset($_POST['deleteSubmit']))
    {
        $managerTable->deleteMedicinePatient($id, $_POST['id']);
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
if(isset($id))
{
   ?>
    <div class="d-flex justify-content-center align-items-centermt-4 mb-4 mt-4 p-0">
        <div class="col-12 col-md-10 col-lg-8">
            <a href="medicinepatient.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">patient list</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-10 col-lg-8">
            <h1 class="display-3 mb-4">Add medicine to <?php echo $patientInfo['FName']?></h1>
            <table class="table table-bordered table-hover">
                <tbody id="myTable">

                    <tr>
                        <th>
                            <h2 class="mb-3">Prescribed</h2>
                        </th>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <th>Schedule</th>
                        <th>Dosage</th>
                    </tr>

                    <?php
                    $allMedicines = $managerMedicine->selectAllMedicine();
                    while($medicineInfo = $allMedicines->fetch())
                    {
                        $reqMedicinePatientInfo = $managerTable->selectMedicinePatient($medicineInfo['ID'], $patientInfo['ID']);
                        $medicinePatientInfo = $reqMedicinePatientInfo->fetch();

                        if($medicinePatientInfo > 0)
                        {
                            ?>
                            <tr>
                                <td><?php echo $medicineInfo['MedDesc'] ?></td>
                                <td><?php echo $medicineInfo['Schedule'] ?></td>
                                <td class="d-flex">
                                    <form class="d-flex" method="post" action="medicinepatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $medicineInfo['ID']?>">
                                        <div class="pl-3 pr-3">
                                            <input class="form-control" type="number" name="dosage" value="<?php echo $medicinePatientInfo['Dosage']?>">
                                        </div>
                                        <div class="pl-3">
                                            <input class="btn btn-primary" type="submit" value="Edit" name="updateSubmit">
                                        </div>
                                    </form>
                                    <form class="d-flex justify-content-around" method="post" action="medicinepatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $medicineInfo['ID']?>">
                                        <div class="pl-3 pr-3">
                                            <input class="btn btn-primary" type="submit" value="Delete" name="deleteSubmit">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr>
                        <th>
                            <h2 class="mb-3">Not prescribed</h2>
                        </th>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <th>Schedule</th>
                        <th>Dosage</th>
                    </tr>

                    <?php
                    $allMedicines = $managerMedicine->selectAllmedicine();
                    while($medicineInfo = $allMedicines->fetch())
                    {
                        $reqMedicinePatientInfo = $managerTable->selectMedicinePatient($medicineInfo['ID'], $patientInfo['ID']);
                        $medicinePatientInfo = $reqMedicinePatientInfo->fetch();

                        if($medicinePatientInfo <= 0)
                        {
                            ?>
                            <tr>
                                <td><?php echo $medicineInfo['MedDesc'] ?></td>
                                <td><?php echo $medicineInfo['Schedule'] ?></td>
                                <td>
                                    <form class="d-flex" method="post" action="medicinepatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $medicineInfo['ID']?>">
                                        <div class="pl-3 pr-3">
                                            <input class="form-control" type="number" name="dosage">
                                        </div>
                                        <div class="pl-3 pr-3">
                                            <input class="btn btn-primary" type="submit" value="Add" name="addSubmit">
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
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
    <div class="d-flex justify-content-center align-items-centermt-4 mb-4 mt-4 p-0">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="index.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">admin</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-3"> Add medicine to patient</h1>
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
                $allPatients = $managerPatient->selectAllPatient();
                while($patientInfo = $allPatients->fetch())
                {
                    ?>

                    <tr>
                        <td><?php echo $patientInfo['FName'] ?></td>
                        <td><?php echo $patientInfo['LName'] ?></td>
                        <td class="d-flex justify-content-around">
                            <a href="medicinepatient.php?id=<?php echo $patientInfo['ID']?>">Medicine</a>
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
