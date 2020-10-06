<?php
session_start();
require('../config/config.php');
require('../lib/bdd.lib.php');
require('../lib/app.lib.php');
userIsConnected();
$errors = [];
$view = 'addUser';
$password = '';
$email = '';
$firstname = '';
$bio = '';
$age = '';
$pictureName = '';
try {

    if (array_key_exists('aut_name', $_POST)) {

        if (array_key_exists('aut_picture', $_FILES)) {


            if ($_FILES['aut_picture']['error'] == 0) {

                $info =    new SplFileInfo($_FILES['aut_picture']['name']);
                if (($info->getExtension()) == 'jpg' || 'png' || 'gif')
                    $pictureName = uniqid() . basename($_FILES['aut_picture']['name']);

                move_uploaded_file($_FILES['aut_picture']['tmp_name'], '../uploads/author/' . $pictureName);
            }
        }
        $password =  password_hash($_POST['aut_password'], PASSWORD_BCRYPT);
        $email = $_POST['aut_email'];
        $firstname = $_POST['aut_name'];
        $bio = $_POST['aut_bio'];
        $age = $_POST['aut_age'];
        $role = $_POST['aut_role'];

        $dbh = connexion();
        $req = $dbh->prepare('SELECT aut_email FROM author WHERE aut_email = :aut_email');
        $req->execute(array(':aut_email' => $email));
        $donnees = $req->fetch();
        if ($donnees != false) // Si une valeur est retournée c'est qu'un membre possède déjà le pseudo.
            $errors[] = 'mail déjà pris';

        if ($_POST['aut_password'] != $_POST['aut_password2'])
            $errors[] = 'les mots de passes ne correspondent pas';


        if (is_numeric($_POST['aut_age']) == false)
            $errors[] = 'veuillez saisir un chiffre';

        if (trim($email) == '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] =  'Erreur email';
        }

        if (trim($firstname) == '')
            $errors[] =  'Erreur firstname ne peut être vide !';

        if (count($errors) == 0) {
            $sth  = $dbh->prepare('INSERT INTO author ( aut_name,aut_bio,aut_picture,aut_age,aut_email,aut_password,aut_role) 
                            VALUES ( :aut_name, :aut_bio,:aut_picture,:aut_age,:aut_email,:aut_password,:aut_role);
                               ');
            $sth->bindValue('aut_role', $_POST['aut_role']);
            $sth->bindValue('aut_name', $_POST['aut_name']);
            $sth->bindValue('aut_bio', $_POST['aut_bio']);
            $sth->bindValue('aut_picture', $pictureName);
            $sth->bindValue('aut_age', $_POST['aut_age']);
            $sth->bindValue('aut_password', $password);
            $sth->bindValue('aut_email', $_POST['aut_email']);
            $sth->execute();
        }
    }
} catch (Exception $e) {
    echo 'Erreur !' . $e->getMessage();
    exit();
}
require('../layout/layout.phtml');
