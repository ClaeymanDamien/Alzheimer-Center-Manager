<?php
/**
 * Created by PhpStorm.
 * User: Damien CLAEYMAN CLEMENT LAMBLING
 * Date: 27/10/2018
 * Time: 15:36
 */

$DBLoaded = isset($_GET['dbloaded']) ? $_GET['dbloaded'] : NULL;
$DBFailed= isset($_GET['dbfailed']) ? $_GET['dbfailed'] : NULL;
?>

<header class="p-0 m-0">
    <nav class="navbar navbar-expand-md bg-light navbar-light min-height-64">
        <a href="<?php echo getRoot()?>index.php">
            <img class="img-fluid height-40" src="<?php echo getRoot()?>images/styles/logo.png" alt="Logo">
        </a>
        <button class="border-0 navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#collapsibleNavId"
                aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
            <img src="<?php echo getRoot()?>images/styles/menu.png" alt="menu" class="img-fluid height-40">
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavId">
            <ul class="navbar-nav mt-2 mt-lg-0">
                <li class="nav-item ">
                    <a class="nav-link" href="<?php echo getRoot()?>index.php">Index</a>
                </li>
                <?php

                /** display the possibilities if we are not logged, logged as an admin or matron so we can see the profile and administrates,
                 * and if we are logged as a user so we can see only the profile
                 */
                if(!empty($userSession)){

                    if($userSession->getStatus() == 'admin' || $userSession->getStatus() == 'matron')
                    {
                        ?>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo getRoot()?>admin/">Admin</a>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo getRoot()?>profile.php">Profile</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo getRoot()?>logout.php">Logout</a>
                    </li>
                    <?php
                }
                else
                {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getRoot()?>login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo getRoot()?>signup.php">Sign up</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
</header>

<?php

if(isset($DBLoaded))
{
    ?>
    <div class="alert alert-primary alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Database has been load
    </div>
    <?php
}
if(isset($DBFailed))
{
    ?>
    <div class="alert alert-primary alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Database has not been load
    </div>
    <?php
}

?>
