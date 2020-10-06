<?php


function userIsConnected()
{
    if ($_SESSION['connected'] !== true) {
        header('Location:login.php');
        exit();
    }
}