<?php
session_start();



$_SESSION['connected'] = false;
unset($_SESSION['connected']);
unset($_SESSION['user']);

header('Location: login.php');
exit();
