<?php
/**
 * Created by PhpStorm.
 * User: flavien_thelliez
 * Date: 14/03/19
 * Time: 11:25
 */

function connexpdo() {
    try {
        $dsn = "sqlite:users.db";
        $db = new PDO($dsn);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        // Print PDOException message
        echo $e->getMessage();
    }
    return $db;
}

session_start();
$db= connexpdo();
$SALT="HODEEiOJFHdIirYURYHFGGT528963HGFH";

if(isset($_POST['uname']) && isset($_POST['pwd'])){
    $query= $db->prepare("SELECT username FROM users WHERE username=?");
    $query->execute(array($_POST['uname']));

    //Password confimation & mirror username verification
    if($_POST['pwd']==$_POST['c_pwd'] && !($res = $query->fetch())){
        $cmdp=crypt($_POST['pwd'],$SALT);
        $query2= $db->prepare("INSERT into users (username, password, admin,desactive,mail,tel)
                  VALUES 
                  (?,?,?,?,?,?)");
        $query2->execute(array($_POST['uname'],$cmdp,0,0,$_POST['mail'],$_POST['tel']));
        echo '<script type="text/javascript">
        alert("Compté créé, vous pouvez vous connecter");
        window.location.replace("login.php");
        </script>';

    }
    else{
        echo "Echec de la création du compte.\n";
        if($_POST['pwd']!=$_POST['c_pwd']){echo "Votre confirmation de mot de passe est invalide";}
        else{echo "Votre nom d'utilisateur est déjà utilisé";}

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
</head>
<body>
<form method="post" action="register.php">
    <label>Nom de compte<input type="text" id="uname" name="uname" required></label><br>
    <label>Mot de Passe<input type="password" id="pwd" name="pwd" required></label><br>
    <label>Confirmez le Mot de Passe<input type="password" id="c_pwd" name="c_pwd" required></label><br>
    <label>Mail<input type="text" id="mail" name="mail" required></label><br>
    <label>Tel<input type="text" id="tel" name="tel" required></label><br>
    <label>Create account<input type="submit" id="submit" name="submit"></label><br>
</form>
</body>
</html>

