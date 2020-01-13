<?php
/**
 * Created by PhpStorm.
 * User: sebastien_bigot
 * Date: 15/03/19
 * Time: 10:41
 */
session_start();
if(isset($_SESSION['adm'])){
    $dbl= new PDO("sqlite:users.db");
    if(isset($_POST['submit'])) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['img']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Erreur, le fichier n'est pas une image";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Extensions autorisée sont uniquement: JPG, JPEG ou PNG";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Erreur fichier, livre pas ajouté";
        } else {
            if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                $query = $dbl->prepare("INSERT INTO livres (titre,auteur,editeur,disponible,path_img) VALUES (?,?,?,?,?)");
                $boolquery = $query->execute(array($_POST['titre'], $_POST['auteur'], $_POST['editeur'], $_POST['num'], $target_file));
            } else {
                echo "erreur lors de l'upload";
            }
        }
    }
    addHeader();
    topnavadmin();
    if(isset($boolquery)) {
        if ($boolquery) {
            echo 'Livre ajouté!';
        } else {
            echo 'Erreur, livre pas ajouté...';
        }
    }

    echo '<form enctype="multipart/form-data" method="post" action="addpage.php">
    <label>Titre<input type="text" id="titre" name="titre" required></label><br>
    <label>Auteur<input type="text" id="auteur" name="auteur" required></label><br>
    <label>Editeur<input type="text" id="editeur" name="editeur" required></label><br>
    <label>Nb d\'ouvrages<input type="number" id="num" name="num" required></label><br>
    <label>Uploader image:<input type="file" name="img" id="img" required></label><br>
    <label>Ajouter<input type="submit" id="submit" name="submit"></label><br>
</form>';
    addFooter();
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


function topnavadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a class="active" href="addpage.php">Ajouter Livre</a>
      <a href="gestusers.php">Gérer utilisateurs</a>
      <a href="gestemprunts.php">Gérer emprunts</a>
      <a href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}

?>


