<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 07/11/2018
 * Time: 13:57
 */

/** Same thing as item manager except some things */
session_start();

require_once(__DIR__ . '/../lib/utilities.php');

$db = PDOConnection::getMysqlConnexion();
$managerItem = new ItemManagerPDO($db);
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

    //If patient is not found
    if($patientInfo<=0 || empty($id))
    {
        header('Location: itempatient.php?errors=np');
    }

    /** Add an item to a patient */
    if(isset($_POST['addSubmit']))
    {
        $itemStockQuantityReq = $managerItem->selectItem($_POST['id']);
        $stockQuantity = $itemStockQuantityReq->fetch();

        $Item = new Item(array(
           'quantity' => $_POST['quantity'],
            'id' => $_POST['id'],
            'stockQuantity' => $stockQuantity['StockItem'],
            'minItem' => $stockQuantity['ItemMinimum'],
            'itemDesc' => $stockQuantity['ItemDesc']
        ));

        $Patient = new Patient(array(
           'id' => $id
        ));

        if($Item->isValidAddPatient())
        {
            /** Change the stock quantity of item */
            $newQuantity = $Item->getStockQuantity() - $Item->getQuantity();
            $Item->setStockQuantity($newQuantity);

            /** Update stock quantity in the database */
            $managerItem->updateItemStockQuantity($Item);

            /** add an item to a patient in database */
            $managerTable->addItemPatient($Patient, $Item);

            /** If there is not enough items it sends an email to the user */
            if($Item->checkStockQuantityMailNotification())
            {
                //request all information to send an email
                $reqPatient = $managerPatient->selectPatient($id);
                $patientInfo = $reqPatient->fetch();

                $Patient->setFName($patientInfo['FName']);
                $Patient->setLName($patientInfo['LName']);

                $reqUser = $managerPatient->selectUser($patientInfo['NextOfKin']);
                $userInfo = $reqUser->fetch();

                $User = new User(array(
                    'lName' => $userInfo['LName'],
                    'fName' => $userInfo['FName'],
                    'email' => $userInfo['Email']
                ));

                //send an email
                mailNotification($Item, $Patient, $User);
            }

            $_POST = array();
        }
        else
        {
            $errors = $Item->getErrors();
        }
    }

    if(isset($_POST['updateSubmit']))
    {
        /** Same thing as addSubmit */
        $itemStockQuantityReq = $managerItem->selectItem($_POST['id']);
        $stockQuantity = $itemStockQuantityReq->fetch();

        $Item = new Item(array(
            'oldQuantity' => $_POST['oldQuantity'],
            'quantity' => $_POST['quantity'],
            'id' => $_POST['id'],
            'stockQuantity' => $stockQuantity['StockItem'],
            'minItem' => $stockQuantity['ItemMinimum'],
            'itemDesc' => $stockQuantity['ItemDesc']
        ));

        $Patient = new Patient(array(
            'id' => $id
        ));

        if($Item->isValidUpdatePatient( $Item->getQuantity() - $Item->getOldQuantity()))
        {

            $Item->setStockQuantity($Item->getStockQuantity() + $Item->getOldQuantity() - $Item->getQuantity());
            $managerItem->updateItemStockQuantity($Item);


            $managerTable->updateItemPatient($Patient, $Item);

            if($Item->checkStockQuantityMailNotification())
            {
                $reqPatient = $managerPatient->selectPatient($id);
                $patientInfo = $reqPatient->fetch();
                $Patient->setFName($patientInfo['FName']);
                $Patient->setLName($patientInfo['LName']);

                $reqUser = $managerPatient->selectUser($patientInfo['NextOfKin']);
                $userInfo = $reqUser->fetch();

                $User = new User(array(
                    'lName' => $userInfo['LName'],
                    'fName' => $userInfo['FName'],
                    'email' => $userInfo['Email']
                ));

                mailNotification($Item, $Patient, $User);
            }

            $_POST = array();
        }
        else
        {
            $errors = $Item->getErrors();
        }
    }

    if(isset($_POST['deleteSubmit']))
    {
        /*
         * Select the item quantity
         */
        $itemStockQuantityReq = $managerItem->selectItem($_POST['id']);
        $stockQuantity = $itemStockQuantityReq->fetch();


        //Select the number of items that the patient possesses
        $quantityReq = $managerTable->selectItemPatient($_POST['id'],$id);
        $quantity = $quantityReq->fetch();

        $Item = new Item(array(
            'oldQuantity' => $quantity['Quantity'],
            'id' => $_POST['id'],
            'stockQuantity' => $stockQuantity['StockItem']
        ));

        $newQuantity = $Item->getStockQuantity() + $Item->getOldQuantity();
        $Item->setStockQuantity($newQuantity);

        $managerItem->updateItemStockQuantity($Item);

        $managerTable->deleteItemPatient($id, $_POST['id']);
    }

    if(isset($errors))
    {
        if(in_array(Item::NOT_ENOUGH_QUANTITY, $errors))
        {
            $messages[] = "Not enough items in stock";
        }
        if(in_array(Item::INVALID_QUANTITY, $errors))
        {
            $messages[] = "Invalid quantity";
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
if(isset($id))
{
   ?>
    <div class="d-flex justify-content-center align-items-centermt-4 mb-4 mt-4 p-0">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="itempatient.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">patient list</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-4">Add item to <?php echo $patientInfo['FName']?></h1>
            <table class="table table-bordered table-hover">
                <tbody id="myTable">
                    <tr>
                        <th>
                            <h2 class="mb-3">Prescribed</h2>
                        </th>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                    </tr>
                    <?php
                    /** If the patient possesses the item */
                    $allItems = $managerItem->selectAllItem();
                    while($itemInfo = $allItems->fetch())
                    {
                        //check if possesses
                        $reqItemPatientInfo = $managerTable->selectItemPatient($itemInfo['ID'], $patientInfo['ID']);
                        $itemPatientInfo = $reqItemPatientInfo->fetch();

                        //if possesses print information and option
                        if($itemPatientInfo > 0)
                        {
                            ?>
                            <tr>
                                <td><?php echo $itemInfo['ItemDesc'] ?></td>
                                <td class="d-flex">
                                    <form class="d-flex" method="post" action="itempatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $itemInfo['ID']?>">
                                        <div class="pl-3 pr-3">
                                            <input id="quantity" class="form-control" type="number" name="quantity" value="<?php echo $itemPatientInfo['Quantity']?>">
                                        </div>
                                        <div class="col-4">
                                            <span>Item in stock: <?php echo $itemInfo['StockItem']?></span>
                                        </div>
                                        <input type="hidden" name="oldQuantity" value="<?php echo $itemPatientInfo['Quantity'] ?>">
                                        <div class="pl-3">
                                            <input class="btn btn-primary" type="submit" value="Edit" name="updateSubmit">
                                        </div>
                                    </form>
                                    <form class="d-flex justify-content-around" method="post" action="itempatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $itemInfo['ID']?>">
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
                        <th>Quantity</th>
                    </tr>

                    <?php
                    /** Same thing if patient not possesses item*/
                    $allItems = $managerItem->selectAllItem();
                    while($itemInfo = $allItems->fetch())
                    {
                        $reqItemPatientInfo = $managerTable->selectItemPatient($itemInfo['ID'], $patientInfo['ID']);
                        $itemPatientInfo = $reqItemPatientInfo->fetch();

                        if($itemPatientInfo <= 0)
                        {
                            ?>
                            <tr>
                                <td><?php echo $itemInfo['ItemDesc'] ?></td>
                                <td>
                                    <form class="d-flex" method="post" action="itempatient.php?id=<?php echo $patientInfo['ID']?>">
                                        <input type="hidden" name="id" value="<?php echo $itemInfo['ID']?>">
                                        <div class="pl-3 pr-3">
                                            <input id="quantity" class="form-control" type="number" name="quantity">
                                        </div>
                                        <div class="col-4">
                                            <span>Item in stock: <?php echo $itemInfo['StockItem']?></span>
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
    /** List of patient */
    ?>
    <div class="d-flex justify-content-center align-items-centermt-4 mb-4 mt-4 p-0">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="index.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">admin</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-3"> Add item to patient</h1>
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
                            <a href="itempatient.php?id=<?php echo $patientInfo['ID']?>">Item</a>
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
