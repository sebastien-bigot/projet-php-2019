<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 28/03/2019
 * Time: 05:34
 */
session_start();
if($_SESSION['adm']!=1){
    echo '<script type="text/javascript">
    alert("Accès refusé");
    window.location.replace("login.php");
    </script>';
}
else{
    addHeader();
    topnavadmin();
    $dbu= new PDO("sqlite:users.db");
    if(isset($_POST['modif'])){
        $livre=unserialize($_POST['modif']);
        $_SESSION['idtmp']=$livre['id'];

        echo '<p>Modifier les informations du livre:</p>
    <form method="post" action="modifpage.php">
    <label>Titre<input type="text" id="titre" name="titre" value="'.$livre['titre'].'" required></label><br>
    <label>Auteur<input type="text" id="auteur" name="auteur" value="'.$livre['auteur'].'" required></label><br>
    <label>Editeur<input type="text" id="editeur" name="editeur" value="'.$livre['editeur'].'" required></label><br>
    <label>Nb d\'ouvrages<input type="number" id="num" name="num" value="'.$livre['disponible'].'" required></label><br>
    <label>Valider<input type="submit" id="submitModif" name="submitModif"></label><br>
    </form>';
    }
    elseif(isset($_POST['submitModif'])){
        $query = $dbu->prepare("UPDATE livres SET titre=?, auteur=?, editeur=?,disponible=? WHERE id=?");
        $query->execute(array($_POST['titre'],$_POST['auteur'],$_POST['editeur'],$_POST['num'],$_SESSION['idtmp']));
        unset($_SESSION['idtmp']);
        echo '<script type="text/javascript">
    alert("Livre modifié, retour page principale");
    window.location.replace("mainpage.php");
    </script>';

    }
    else{
        echo "Vous n'avez rien à faire là!!!";
    }
}
?>


<?php
function topnavadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a href="addpage.php">Ajouter Livre</a>
      <a href="gestusers.php">Gérer utilisateurs</a>
      <a href="gestemprunts.php">Gérer emprunts</a>
      <a href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
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
</html>

';
}
?>
