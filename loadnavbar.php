<!-- Author: Andrea Morales -->
<!-- Changes the nav dependig if you are logged or role -->
<?php
/**
 * navigation bar 
 */

if (isset($_SESSION['userrole'])) {
    $userrole = ($_SESSION['userrole']);
    if ($userrole) {
        switch ($userrole) {
            case "admin":
                $menupath = "views/admin/adminmenu.php";
                break;
            case "staff":
                $menupath = "views/staff/staffmenu.php";       
        }
    }
}else{
    $menupath = "views/mainmenu.php"; //default value.
}

//include proper menu.
include $menupath;