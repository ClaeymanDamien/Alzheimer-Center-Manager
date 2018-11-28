<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 24/10/2018
 * Time: 15:26
 */

session_start();

require_once(__DIR__ . '/lib/utilities.php');

$userSession = getSessionUser();

/** redirection */
if(empty($userSession))
{
   header('Location: index.php');
}

/** connection to the database */
$db = PDOConnection::getMysqlConnexion();
$managerUser = new UserManagerPDO($db);
$managerItem = new ItemManagerPDO($db);
$managerMedicine = new MedicineManagerPDO($db);
$managerLinkTable = new LinkTableManagerPDO($db);


$patients = $managerUser->selectForeignPatient($userSession->getId());

?>
<!-- We includes the header, navbar -->
<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php')?>
</head>
<body>
<?php include('includes/navbar.php')?>

<div class="container-fluid m-0 mt-5 row">
   <div class="p-3 col-12 col-sm-6 col-md-4 col-lg-3">
        <h4>User :</h4>
        <div class="card col-12 p-0 m-0">
           <div class="profile-img">
               <?php
                /** we get the information of the user */
               $reqUser = $managerUser->selectUser($userSession->getId());
               $userData = $reqUser->fetch();

                /** if the file exists we display the file, if not we display a default picture */
               $filename = $userData['UserImage'];
               if(file_exists($filename))
               {
                   ?>
                   <img src="<?php echo $filename?>" alt="user" class="card-img-top">
                   <?php
               }
               else
               {
                   ?>
                   <img src="images/styles/profile.jpg" alt="user" class="card-img-top">
                   <?php
               }
               ?>
           </div>
            <!-- Display the profile -->
            <div class="card-body">
                <h5 class="card-title"><?php echo $userData['FName'].' '.$userData['LName']?></h5>
                <p class="card-text">
                    Email = <?php echo $userData['Email'] ?> <br>
                    Cell Num = <?php echo $userData['CellNum'] ?> <br>
                    Address 1 = <?php echo $userData['Address1'] ?> <br>
                    Address 2 = <?php echo $userData['Address2'] ?> <br>
                    Postal Code = <?php echo $userData['PostalCode'] ?> <br>
                    Status = <?php echo $userData['Status'] ?> <br>
                </p>
            </div>
        </div>
    </div>

    <!-- We get the information of the patient and then display it -->
<?php
$i = 1;
while($data = $patients->fetch())
{

?>

    <div class="p-3 col-12 col-sm-6 col-md-4 col-lg-3">
        <h4>Patient <?php echo $i ?> : </h4>
        <div class="card col-12 p-0 m-0">
            <div class="profile-img">
                <?php
                $filename = $data['PatientImage'];
                if(file_exists($filename))
                {
                    ?>
                    <img src="<?php echo $data['PatientImage']?>" alt="patient" class="card-img-top">
                    <?php
                }
                else
                {
                    ?>
                    <img src="images/styles/profile.jpg" alt="user" class="card-img-top">
                    <?php
                }
                ?>
            </div>

            <div class="card-body">
                <h5 class="card-title"><?php echo $data['FName'].' '.$data['LName']?></h5>
                <p class="card-text">
                    Room number = <?php echo $data['RoomNb'] ?> <br>
                    Grade classification = <?php echo $data['GradeClassification'] ?> <br>
                    Address 1 = <?php echo $data['Address1'] ?> <br>
                    Address 2 = <?php echo $data['Address2'] ?> <br>
                    Postal Code = <?php echo $data['PostalCode'] ?> <br>
                </p>
                <div>
                    <h4>Item(s)</h4>
                    <?php
                    $itemPatientReq = $managerLinkTable->selectAllItemPatient($data['ID']);

                    while ($itemPatientData = $itemPatientReq->fetch())
                    {
                        $itemFound = true;
                        $itemReq = $managerItem->selectItem($itemPatientData['tbl_Item_ID']);
                        $itemData = $itemReq->fetch();

                        ?>
                        <p>
                            Item Desc: <?php echo $itemData['ItemDesc']?><br>
                            Quantity: <?php echo $itemPatientData['Quantity']?>
                        </p>
                        <?php
                            if($itemData['ItemPic'] != "unknown")
                            {
                                ?>
                                <img class="img-fluid rounded" src="<?php echo $itemData['ItemPic']?>" alt="itemPic">
                                <?php
                            }
                        ?>
                        <hr>
                        <?php
                    }
                    if(!isset($itemFound))
                    {
                        ?>
                        <span>No item found</span>
                        <?php
                    }
                    ?>
                </div>
                <div class="pt-3">
                    <h4>Medicine(s)</h4>
                    <?php
                    $itemMedicineReq = $managerLinkTable->selectAllItemMedicine($data['ID']);

                    while ($medicinePatientData = $itemMedicineReq->fetch())
                    {
                        $medicineFound = true;
                        $medicineReq = $managerMedicine->selectMedicine($medicinePatientData['tbl_Medicine_ID']);
                        $medicineData = $medicineReq->fetch();

                        ?>
                        <p>
                            Medicine Desc: <?php echo $medicineData['MedDesc']?><br>
                            Schedule: <?php echo $medicineData['Schedule']?><br>
                            Dosage: <?php echo $medicinePatientData['Dosage']?><br>
                        </p>
                        <hr>
                        <?php

                    }
                    if(!isset($medicineFound))
                    {
                        ?>
                        <span>No medicine found</span>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


<?php
    $i++;
}
?>
</div>

<?php include 'includes/footer.php'?>


<?php include('includes/script.php')?>
</body>
</html>