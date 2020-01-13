<?php
/**
 * Created by PhpStorm.
 * User: sebastien_bigot
 * Date: 08/03/19
 * Time: 10:14
 */

session_start();
if(isset($_POST['submittaille'])){
    setcookie("tailleimg",$_POST['taille'],time()+604800);
}
if(isset($_COOKIE['tailleimg'])) {
    $img_size = $_COOKIE['tailleimg'];
}
else{
    $img_size="50%";
}
$dbu= new PDO("sqlite:users.db");
if(isset($_SESSION['uname'])) {
    addHeader();
    if(isset($_POST['submittaille'])){
        $img_size=$_POST['submittaille'];
    }
    $query = $dbu->prepare("SELECT admin FROM users WHERE username=?");
    $result = $query->execute(array($_SESSION['uname']));
    $row = $query->fetch();
    $admbool = $row['admin'];
    if ($admbool) {
        $_SESSION['adm'] = 1;
        topnavadmin();
    } else {
        topnavnotadmin();
    }
    $queryemprunt = $dbu->prepare("SELECT echeance FROM emprunt WHERE id_user=?");
    $queryemprunt->execute(array($_SESSION['uname']));
    $dateact=date("d/m/Y",time());
    $booldep=false;
    while ($row = $queryemprunt->fetch()){
        if ($dateact>$row['echeance']){
            $booldep=true;
        }
    }
    if($booldep){
        echo '<script type="text/javascript">
    alert("Vous avez des emprunts ayant dépassé la date limite!!!");
    </script>';
    }

    if(isset($_POST['delete'])){
        $queryD=$dbu->prepare("DELETE FROM livres WHERE id=?");
        $queryD->execute(array($_POST['delete']));
    }
    echo '<p> Bienvenue sur votre compte,' . $_SESSION['uname'] . '</p><br><br>';
    echo '<form method="post" action="mainpage.php">
    <label for="recherche">Rechercher un livre:</label>
    <input type="search" id="recherche" name="recherche" required><br>
    <label>Titre<input type="radio" id="titre" name="critere" value="titre" checked></label><br>
    <label>Auteur<input type="radio" id="auteur" name="critere" value="auteur"></label><br>
    <label>Editeur<input type="radio" id="editeur" name="critere" value="editeur"></label><br>
    <input type="submit" id="search" name="search" value="Rechercher">
</form>';
    if (isset($_POST['search'])) {
        echo "<table>
        <thead>
        <tr>
            <th scope=\"col\">id</th>
            <th scope=\"col\">image</th>
            <th scope=\"col\">titre</th>
            <th scope=\"col\">auteur</th>
            <th scope=\"col\">editeur</th>
            <th scope=\"col\">réserver</th>";
        if(isset($_SESSION['adm'])) {
            echo "<th scope=\"col\">supprimer</th>
            <th scope=\"col\">modifier</th>";
        }
        echo "</tr>
        </thead>
        <tbody>";
        if($_POST['critere']=="titre") {
            //echo'TEST titre';
            $query = $dbu->prepare('SELECT * FROM livres WHERE titre LIKE ?');
        }
        elseif ($_POST['critere']=="auteur"){
            //echo 'TEST AUTEUR';
            $query = $dbu->prepare('SELECT * FROM livres WHERE auteur LIKE ?');
        }
        else{
            //echo 'TEST editeur';
            $query = $dbu->prepare('SELECT * FROM livres WHERE editeur LIKE ?');
        }
        //Edition de la variable pour permettre la recherche partielle.
        $_POST['recherche'] = '%'.$_POST['recherche'].'%';
        $query->execute(array($_POST['recherche']));

        while ($row = $query->fetch()) {
            echo "<tr>
             <th scope=\"row\">" . $row["id"] . "</th>
             <td><img src=\"" . $row["path_img"] . "\" width='".$img_size."' height='".$img_size."' /></td>
             <td>" . $row["titre"] . "</td>
             <td>" . $row["auteur"] . "</td>
             <td>" . $row["editeur"] . "</td>";
            echo "<td><form method='post' action='mainpage.php'><button type='submit' name='reserver' value='" . $row["id"] . "'>Réserver</button></form></td>";
            if(isset($_SESSION['adm'])&&$_SESSION['adm']==1){
                echo "<td><form method='post' action='mainpage.php'><button type='submit' name='delete' value='" . $row["id"] . "'>Supprimer</button></form></td>";
                echo "<td><form method='post' action='modifpage.php'><button type='submit' name='modif' value='".serialize($row)."'>Modifier</button></form></td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
        addFooter();

    }

    //Si on demande une réservation
    if(isset($_POST['reserver'])){
        reserve($_POST['reserver']);
    }

}
else {
    echo '<script type="text/javascript">
alert("Vous n\'êtes pas connecté");
window.location.replace("login.php");
</script>';
}

function topnavadmin(){
    echo ' <div class="topnav">
      <a class="active" href="mainpage.php">Recherche</a>
      <a href="addpage.php">Ajouter Livre</a>
      <a href="gestusers.php">Gérer utilisateurs</a>
      <a href="gestemprunts.php">Gérer emprunts</a>
      <a href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}

function topnavnotadmin(){
    echo ' <div class="topnav">
      <a class="active" href="mainpage.php">Recherche</a>
      <a href="user_book.php">Mes livres</a>
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
    <style>
    table {border:2px solid black; border-spacing:1px; margin:20px}
    td {border:1px solid black}
    th {border:1px solid black}
  </style>
</head>
<body>';
}
function addFooter(){
    echo'</body>
</html>

';
}

function reserve($id) {
    //Gérer une réservation.
    global $dbu,$query;
    $dbu=null;
    $query=null;
    $dbu=new PDO("sqlite:users.db");

    $query=$dbu->prepare("Select * from livres where id=?");
    $query->execute(array($id));
    $row = $query->fetch();
    $titre = $row['titre'];
    $auteur = $row['auteur'];
    $editeur = $row['editeur'];

    $utilisateur = $_SESSION['uname'];

    $query = $dbu->prepare("INSERT INTO reservation (id_book,id_user,titre,auteur,editeur) VALUES (?,?,?,?,?) ;");
    $query->execute(array($id,$utilisateur,$titre,$auteur,$editeur));
}