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
$manager = new MedicineManagerPDO($db);
$managerLinkTable = new LinkTableManagerPDO($db);

$userSession = getSessionUser();

if(empty($userSession) || !($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron'))
{
    header('Location: index.php');
}

$register = isset($_GET['register']) ? $_GET['register'] : NULL;
$delete = isset($_GET['delete']) ? $_GET['delete'] : NULL;
$edit = isset($_GET['edit']) ? $_GET['edit'] : NULL;
$medicineList = isset($_GET['medicinelist']) ? $_GET['medicinelist'] : NULL;


if(isset($_GET['updated']))
{
    $messages[] = "Medicine is updated";
}
if(isset($_GET['deleted']))
{
    $messages[] = "Medicine is deleted";
}
if(isset($_GET['added']))
{
    $messages[] = "Medicine is added";
}

/** Add an medicine */
if(isset($_POST['addSubmit']))
{

    $medicine = new medicine(array(
        'medDesc' => $_POST['description'],
        'schedule' => $_POST['schedule']
    ));

    if($medicine->isValid())
    {
        $manager->add($medicine);
        header('Location: medicinemanager.php?register&added');
    }
    else
    {
        $errors = $medicine->getErrors();
    }

}

/** Delete an medicine */
if(isset($_POST['deleteSubmit']) && isset($delete))
{
    $managerLinkTable->deleteMedicine($delete);
    $manager->delete($delete);

    header('Location: medicinemanager.php?deleted');
}

/** Edit an medicine */
if(isset($edit))
{
    $medicineInfoReq = $manager->selectMedicine($edit);
    $medicineInfo = $medicineInfoReq->fetch();


    if($medicineInfo <= 0)
    {
        header('Location: medicinemanager.php');
    }

    if(isset($_POST['editSubmit']))
    {
        $medicine = new medicine(array(
            'id' => $edit,
            'medDesc' => empty($_POST['description']) ? $medicineInfo['MedDesc'] : $_POST['description'],
            'schedule' => empty($_POST['schedule']) ? $medicineInfo['Schedule'] : $_POST['schedule']
        ));



        if($medicine->isValid())
        {
            $manager->update($medicine);

            header('Location: medicinemanager.php?edit='.$medicine->getId().'&updated');
        }
        else
        {
            $errors = $medicine->getErrors();
        }
    }
}

if(isset($errors))
{
    if(in_array(Medicine::INVALID_DESCRIPTION,$errors)){
        $messages[] = "Description is invalid";
    }
    if(in_array(Medicine::INVALID_SCHEDULE,$errors)){
        $messages[] = "Schedule is invalid";
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
    <div class="d-flex justify-content-center align-medicines-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="medicinemanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">medicine manager</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3">Add a new medicine</h1>

            <form class="mb-4" action="medicinemanager.php?register"  method="post">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['description']))
                        echo $_POST['description'];
                    ?></textarea>

                <label for="schedule">Schedule :</label>
                <textarea name="schedule" id="schedule" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['schedule']))
                        echo $_POST['schedule'];
                    ?></textarea>

                <input type="submit" name="addSubmit" class="btn btn-block btn-primary" value="Send">
            </form>

        </div>
    </div>
    <?php
}
elseif (isset($delete))
{
    ?>
    <div class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6 border mt-5 p-3">
        <h4 class="p-3">Are you sure you want to delete this medicine?</h4>
        <div class="row">
            <div class="col-6 pl-3 pr-3">
                <form action="medicinemanager.php?delete=<?php echo $delete?>" method="post">
                    <input type="submit" name="deleteSubmit" class="btn btn-block btn-outline-primary" value="Yes">
                </form>
            </div>
            <div class="col-6 pl-3 pr-3">
                <a href="medicinemanager.php?medicinelist" class="btn btn-block btn-primary">No</a>
            </div>
        </div>
    </div>
    <?php
}
elseif (isset($edit))
{
    ?>
    <div class="d-flex justify-content-center align-medicines-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="medicinemanager.php?medicinelist" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">medicine list</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">

            <h1 class="display-3">Edit a new medicine</h1>

            <form class="mb-4" action="medicinemanager.php?edit=<?php echo $edit?>" enctype="multipart/form-data" method="post">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['description']) && !empty($_POST['description']))
                        echo $_POST['description'];
                    else if(isset($medicineInfo))
                        echo $medicineInfo['MedDesc'];
                    ?></textarea>
                <textarea name="schedule" id="schedule" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['schedule']) && !empty($_POST['schedule']))
                        echo $_POST['schedule'];
                    else if(isset($medicineInfo))
                        echo $medicineInfo['Schedule'];
                    ?></textarea>
                <input type="submit" name="editSubmit" class="btn btn-block btn-primary" value="Send">
            </form>
        </div>
    </div>
    <?php
}
elseif (isset($medicineList))
{

    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="medicinemanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">medicine manager</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-3">Medicine List</h1>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Schedule</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $allMedicines = $manager->selectAllmedicine();
                while($medicineInfo = $allMedicines->fetch())
                {
                    ?>

                    <tr>
                        <td><?php echo $medicineInfo['MedDesc'] ?></td>
                        <td><?php echo $medicineInfo['Schedule'] ?></td>
                        <td class="d-flex justify-content-around">
                            <a href="medicinemanager.php?edit=<?php echo $medicineInfo['ID']?>">Edit</a>
                            <a href="medicinemanager.php?delete=<?php echo $medicineInfo['ID']?>">Delete</a>
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
    <div class="container-fluid mt-4 mb-4 pl-4">
        <div class="pl-4 col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6">
            <a href="index.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">admin</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3 p-3">Medicine Manager</h1>
            <div class="d-flex justify-content-between">
                <div class="col-6 p-3">
                    <a href="medicinemanager.php?register" class="btn btn-block btn-primary">Add</a>
                </div>
                <div class="col-6 p-3">
                    <a href="medicinemanager.php?medicinelist" class="btn btn-block btn-primary">List</a>
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
