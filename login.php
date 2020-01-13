<?php
/**
 * Created by PhpStorm.
 * User: sebastien_bigot
 * Date: 08/03/19
 * Time: 09:41
 */
session_start();
if(isset($_SESSION['uname'])) {
    session_destroy();
    session_start();
}
$SALT="HODEEiOJFHdIirYURYHFGGT528963HGFH";
$db= new PDO("sqlite:users.db");
if(isset($_POST['uname']) && isset($_POST['pwd'])){
    $query= $db->prepare("SELECT password FROM users WHERE username=?");
    $query->execute(array($_POST['uname']));

    if($res= $query->fetch()){
        $cmdp=crypt($_POST['pwd'],$SALT);
        if($cmdp==$res['password']){
            $_SESSION['uname']=$_POST['uname'];
            //Passé en commentaire pour tester des liens plus court, sans problème de serveur local
            //header('Location: http://172.31.143.252/~sebastien_bigot/ProjPWEB/mainpage.php');
            header('Location: mainpage.php');
            echo "test connexion";
        }
        else{
            echo 'MDP incorrect';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
<form method="post" action="login.php">
    <label>Nom de compte<input type="text" id="uname" name="uname" required></label><br>
    <label>Mot de Passe<input type="password" id="pwd" name="pwd" required></label><br>
    <label>Connexion<input type="submit" id="submit" name="submit"></label><br>
</form>
<input type="button" value="S'inscrire" class="regBtn" id="register"
       onClick="window.location = 'register.php'" />
</body>
</html>

