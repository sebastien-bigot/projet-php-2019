<?php
/**
 * Created by PhpStorm.
 * User: sebastien_bigot
 * Date: 08/03/19
 * Time: 10:37
 */
session_start();
$db= new PDO("sqlite:users.db");
$SALT="HODEEiOJFHdIirYURYHFGGT528963HGFH";
if(isset($_SESSION['uname'])){
    addHeader();
    if(isset($_SESSION['adm'])  ){
        topnavadmin();
    }
    else{
        topnavnotadmin();
    }
    $query= $db->prepare("SELECT admin FROM users WHERE username=?");
    $result = $query->execute(array($_SESSION['uname']));
    $row = $query->fetch();
    if($row['admin']==1){
        //Partie commandes admin
    }
    if(isset($_POST['submitpwd'])){
        if($_POST['npwd'] == $_POST['cpwd']){
            $query= $db->prepare("UPDATE users SET password=? WHERE username=?");
            $query->execute(array(crypt($_POST['npwd'],$SALT),$_SESSION['uname']));
        }
        else{
            echo 'Echec changement mdp';
        }
        if(isset($_POST['mail'])){
            $query2= $db->prepare("UPDATE users SET mail=? WHERE username=?");
            $query2->execute(array($_POST['mail'],$_SESSION['uname']));
        }
        if(isset($_POST['tel'])){
            $query2= $db->prepare("UPDATE users SET tel=? WHERE username=?");
            $query2->execute(array($_POST['tel'],$_SESSION['uname']));
        }
    }
    echo '<form method="post" action="settings.php">
<fieldset>
    <legend>Modifier ses informations perso:</legend>
    <label>Nouveau mot de passe<input type="password" id="npwd" name="npwd" required></label><br>
    <label>Confirmer mot de passe<input type="password" id="cpwd" name="cpwd" required></label><br>
    <label>Mail<input type="text" id="mail" name="mail"></label><br>
    <label>Tel<input type="text" id="tel" name="tel"></label><br>
    <input type="submit" id="submitpwd" name="submitpwd" value="Changer informations"><br>
    </fieldset>
</form>';

    echo '<form method="post" action="mainpage.php">
<fieldset>
    <legend>Sélectionner taille des images:</legend>
    <label>Originale (pas de modification)<input type="radio" id="taille" name="taille" value="100%" required></label><br>
    <label>Grande (75%)<input type="radio" id="taille" name="taille" value="75%"></label><br>
    <label>Moyenne (50% - Taille par défaut)<input type="radio" id="taille" name="taille" value="50%" checked></label><br>
    <label>Petite (25%)<input type="radio" id="taille" name="taille" value="25%"></label><br>
    <input type="submit" id="submittaille" name="submittaille" value="Changer taille images"><br>
    </fieldset>
</form>';
    addFooter();
}
else {
    echo '<script type="text/javascript">
    alert("Vous n\'êtes pas connecté");
    window.location.replace("login.php");
    </script>';
}

function addHeader(){
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Connexion</title>
    </head>
    <body>';
}
function addFooter(){
    echo'</body>
    </html>';
}

function topnavadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a href="addpage.php">Ajouter Livre</a>
      <a href="gestusers.php">Gérer utilisateurs</a>
      <a href="gestemprunts.php">Gérer emprunts</a>
      <a class="active" href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}

function topnavnotadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a href="user_book.php">Mes livres</a>
      <a class="active" href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}