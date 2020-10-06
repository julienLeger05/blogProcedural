<?php
session_start();
require('../lib/app.lib.php');
userIsConnected();
$view = 'index';



if ($_SESSION['connected'] === true)
    1 == 1;

else {
    header('Location: login.php');
    exit();
}


require('../layout/layout.phtml');
