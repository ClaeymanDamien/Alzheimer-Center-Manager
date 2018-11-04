<?php
/**
 * Created by PhpStorm.
 * User: dclae
 * Date: 24/10/2018
 * Time: 15:26
 */

session_start();
include('lib/autoloadRoot.php');
include('lib/tools.php');

$userSession = getSessionUser();


if(empty($userSession))
{
   header('Location: index.php');
}


$db = PDOFactory::getMysqlConnexion();
$manager = new UserManagerPDO($db);

$patients = $manager->selectForeignPatient($userSession->getId());

?>

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
               $filename = $userSession->getPicturePath();
               if(file_exists($filename))
               {
                   ?>
                   <img src="<?php echo $userSession->getPicturePath()?>" alt="user" class="card-img-top">
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
                <h5 class="card-title"><?php echo $userSession->getFName().' '.$userSession->getLName()?></h5>
                <p class="card-text">
                    Email = <?php echo $userSession->getEmail() ?> <br>
                    Cell Num = <?php echo $userSession->getCellNum() ?> <br>
                    Address 1 = <?php echo $userSession->getAddress1() ?> <br>
                    Address 2 = <?php echo $userSession->getAddress2() ?> <br>
                    Postal Code = <?php echo $userSession->getPostalCode() ?> <br>
                    Status = <?php echo $userSession->getStatus() ?> <br>
                </p>
            </div>
        </div>
    </div>


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
            </div>
        </div>
    </div>


<?php
    $i++;
}
?>
</div>

<?php include('includes/script.php')?>
</body>
</html>