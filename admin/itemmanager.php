<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 06/11/2018
 * Time: 11:59
 */

session_start();

require_once(__DIR__ . '/../lib/utilities.php');

//Database connection
$db = PDOConnection::getMysqlConnexion();
$manager = new ItemManagerPDO($db);
$managerLinkTable = new LinkTableManagerPDO($db);

//Unserialize object
$userSession = getSessionUser();

//Check if user is connected
if(empty($userSession) || !($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron'))
{
    header('Location: index.php');
}

//Recover GET data
$register = isset($_GET['register']) ? $_GET['register'] : NULL;
$delete = isset($_GET['delete']) ? $_GET['delete'] : NULL;
$edit = isset($_GET['edit']) ? $_GET['edit'] : NULL;
$itemList = isset($_GET['itemlist']) ? $_GET['itemlist'] : NULL;

//Create notification message with GET data
if(isset($_GET['updated']))
{
    $messages[] = "Item is updated";
}
if(isset($_GET['deleted']))
{
    $messages[] = "Item is deleted";
}
if(isset($_GET['added']))
{
    $messages[] = "Item is added";
}

/** Add an item */
//if the form for add a item is sent
if(isset($_POST['addSubmit']))
{
    //create an object with the information
    $item = new Item(array(
        'id' => $manager->getLastInsertId()+1,
        'stockQuantity' => $_POST['stockItem'],
        'itemDesc' => $_POST['description'],
        'minItem' => $_POST['minimum'],
        'pictureName' => $_FILES['picture']['name'],
        'pictureTmpName' => $_FILES['picture']['tmp_name']
    ));

    //check if the data of object are valid
    if($item->isValid())
    {
        //Check if there is a picture
        if(!empty($item->getPictureName()))
        {
            $item->addPicture(); //Upload the picture file in a folder
        }
        $manager->add($item); //add item in database

        header('Location: itemmanager.php?register&added');
    }
    else
    {
        $errors = $item->getErrors(); //Recover object error
    }

    //Create messages depending on the errors
    if(isset($errors))
    {
        if(in_array(Item::INVALID_DESCRIPTION,$errors)){
            $messages[] = "Description is invalid";
        }
        if(in_array(Item::INVALID_EXTENSION,$errors)){
            $messages[] = "Picture extension is invalid";
        }
        if(in_array(Item::FAIL_MOVE_PICTURE,$errors)){
            $messages[] = "Fail move picture";
        }
        if(in_array(Item::INVALID_MIN_QUANTITY,$errors)){
            $messages[] = "Invalid min quantity";
        }

    }
}

/** Delete an item */
if(isset($_POST['deleteSubmit']) && isset($delete))
{
    $managerLinkTable->deleteItem($delete);
    $manager->delete($delete);

    header('Location: itemmanager.php?itemlist&deleted');
}

/** Edit an item */
if(isset($edit))
{
    //recover item information
    $itemInfoReq = $manager->selectItem($edit);
    $itemInfo = $itemInfoReq->fetch();

    //if item don't exist
    if($itemInfo <= 0)
    {
        header('Location: itemmanager.php');
    }

    if(isset($_POST['editSubmit']))
    {
        //Create a new item with the old information or the new if new information are send
        $item = new Item(array(
            'id' => $edit,
            'itemDesc' => empty($_POST['description']) ? $itemInfo['ItemDesc'] : $_POST['description'],
            'minItem' => empty($_POST['minimum']) ? $itemInfo['ItemMinimum'] : $_POST['minimum'],
            'stockQuantity' => empty($_POST['stockItem']) ? $itemInfo['StockItem'] : $_POST['stockItem'],
            'pictureName' => $_FILES['picture']['name'],
            'pictureTmpName' => $_FILES['picture']['tmp_name']
        ));


        //Check if the information are valid
        $valid = true;
        if(!$item->isValid())
        {
            $valid = false;
        }

        if(!empty($item->getPictureTmpName()))
        {
            if(!$item->addPicture())
                $valid = false;
        }

        //If valid update
        if($valid)
        {
            $manager->update($item);
            header('Location: itemmanager.php?edit='.$itemInfo['ID'].'&updated');
        }
        else
        {
            $errors = $item->getErrors();
        }

        //else generate errors
        if(isset($errors))
        {
            if(in_array(Item::INVALID_DESCRIPTION,$errors)){
                $messages[] = "Description is invalid";
            }
            if(in_array(Item::INVALID_EXTENSION,$errors)){
                $messages[] = "Picture extension is invalid";
            }
            if(in_array(Item::FAIL_MOVE_PICTURE,$errors)){
                $messages[] = "Fail move picture";
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
//print errors messages
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
/** Part add an item */
if(isset($register))
{
    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="itemmanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">item manager</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3">Add a new item</h1>
            <form class="mb-4" action="itemmanager.php?register" enctype="multipart/form-data" method="post">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['description']))
                        echo $_POST['description'];
                    ?></textarea>
                <label for="stockItem">Item in stock :</label>
                <input name="stockItem" type="number" id="stockItem" class="form-control mb-3" value="<?php
                if(isset($_POST['stockItem']))
                    echo $_POST['stockItem'];
                ?>">
                <label for="minimum">Item minimum before notification : </label>
                <input name="minimum" type="number" id="minimum" class="form-control mb-3" value="<?php
                if(isset($_POST['minimum']))
                    echo $_POST['minimum'];
                ?>">
                <div class="d-flex flex-column mb-3">
                    <label>Picture :</label>
                    <input type="file" name="picture">
                </div>
                <input type="submit" name="addSubmit" class="btn btn-block btn-primary" value="Send">
            </form>
        </div>
    </div>
    <?php
}
/** Part delete an item */
elseif (isset($delete))
{
    ?>
    <div class="col-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6 border mt-5 p-3">
        <h4 class="p-3">Are you sure you want to delete this item?</h4>
        <div class="row">
            <div class="col-6 pl-3 pr-3">
                <form action="itemmanager.php?delete=<?php echo $delete?>" method="post">
                    <input type="submit" name="deleteSubmit" class="btn btn-block btn-outline-primary" value="Yes">
                </form>
            </div>
            <div class="col-6 pl-3 pr-3">
                <a href="itemmanager.php?itemlist" class="btn btn-block btn-primary">No</a>
            </div>
        </div>
    </div>
    <?php
}
/** Part edit an item */
elseif (isset($edit))
{
    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="itemmanager.php?itemlist" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">list item</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <h1 class="display-3">Edit a new item</h1>
            <form class="mb-4" action="itemmanager.php?edit=<?php echo $itemInfo['ID']?>" enctype="multipart/form-data" method="post">
                <label for="description">Description :</label>
                <textarea name="description" id="description" class="form-control mb-3" rows="5"><?php
                    if(isset($_POST['description']) && !empty($_POST['description'])) //fill the form with old or new information
                        echo $_POST['description'];
                    else if(isset($itemInfo))
                        echo $itemInfo['ItemDesc'];
                    ?></textarea>
                <label for="stockItem">Item in stock :</label>
                <input name="stockItem" type="number" id="stockItem" class="form-control mb-3" value="<?php
                if(isset($_POST['stockItem']))
                    echo $_POST['stockItem'];
                else if(isset($itemInfo))
                    echo $itemInfo['StockItem'];
                ?>">
                <label for="minimum">Item minimum before notification :</label>
                <input name="minimum" type="number" id="minimum" class="form-control mb-3" value="<?php
                if(isset($_POST['minimum']))
                    echo $_POST['minimum'];
                else if(isset($itemInfo))
                    echo $itemInfo['ItemMinimum'];
                ?>">
                <div class="d-flex flex-column mb-3">
                    <?php
                    if($itemInfo['ItemPic'] != "unknown")
                    {
                        ?>
                        <img class="img-fluid rounded" src="<?php echo getRoot().$itemInfo['ItemPic']?>" alt="item">
                        <?php
                    }
                    ?>
                    <label>Picture :</label>
                    <input type="file" name="picture">
                </div>
                <input type="submit" name="editSubmit" class="btn btn-block btn-primary" value="Send">
            </form>
        </div>
    </div>
    <?php
}
/** Part list of all items */
elseif (isset($itemList))
{

    ?>
    <div class="d-flex justify-content-center align-items-center p-0 mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
            <a href="itemmanager.php" class="p-0 m-0 btn btn-link text-dark">
                <img class="mr-2" src="../images/styles/ic_arrow_back_black_24dp.png" alt="return_row">item manager</a>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="col-12 col-md-8 col-lg-6">
            <h1 class="display-3 mb-3">Item list</h1>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="myTable">
                <?php
                $allItems = $manager->selectAllItem();
                while($itemInfo = $allItems->fetch())
                {
                    ?>

                    <tr>
                        <td><?php echo $itemInfo['ItemDesc'] ?></td>
                        <td class="d-flex justify-content-around">
                            <a href="itemmanager.php?edit=<?php echo $itemInfo['ID']?>">Edit</a>
                            <a href="itemmanager.php?delete=<?php echo $itemInfo['ID']?>">Delete</a>
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
/** Index article manager */
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
            <h1 class="display-3 p-3">Item Manager</h1>
            <div class="d-flex justify-content-between">
                <div class="col-6 p-3">
                    <a href="itemmanager.php?register" class="btn btn-block btn-primary">Add</a>
                </div>
                <div class="col-6 p-3">
                    <a href="itemmanager.php?itemlist" class="btn btn-block btn-primary">List</a>
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
