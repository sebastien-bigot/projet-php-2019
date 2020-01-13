<?php
/**
 * Created by PhpStorm.
 * User: CTFla
 * Date: 27/03/2019
 * Time: 13:00
 */

session_start();
if(isset($_SESSION['adm'])){
    addHeader();
    topnavadmin();
    $dbu= new PDO("sqlite:users.db");
}
else{
    echo '<script type="text/javascript">
    alert("Accès refusé");
    window.location.replace("login.php");
    </script>';
}

?>
<form method="post" action="gestemprunts.php">
    <p>Rentrez l'ID d'un utilisateur pour gérer ses emprunts :</p>
    <label>ID utilisateur<input type="text" id="uID" name="uID" min="0" required></label><br>
    <label>Voir les emprunts<input type="submit" id="voir_emprunt" name="voir_emprunt"></label><br>
    <label>Voir les réservation<input type="submit" id="voir_reserv" name="voir_reserv"></label><br>
</form><br>
<?php

if(isset($_POST['voir_emprunt'])){
    echo "Voici les emprunts de l'utilisateur ".$_POST['uID'];

    echo "<table>
        <thead>
        <tr>
            <th scope=\"col\">titre</th>
            <th scope=\"col\">échéance</th>
            
        </tr>
        </thead>
        <tbody>";

    //$query = $dbu->prepare('SELECT * FROM emprunt WHERE utilisateur LIKE ?');
    //$query->execute(array($_SESSION['uname']));
    $query = $dbu->prepare('SELECT * FROM emprunt WHERE id_user LIKE ?');
    $query->execute(array($_POST['uID']));

    while ($row = $query->fetch()) {
        echo "<tr>
            <td>" . $row["titre"] . "</td>
            <td>" . $row["echeance"] . "</td>
            <td><form method='post' action='gestemprunts.php'><button type='submit' name='retour' value='" . serialize($row) . "'>Retour</button></form></td>";
    }
    echo "</tr>
        </tbody>
        </table>";
}
if(isset($_POST['retour'])){
    $book = unserialize($_POST['retour']);

    //Suppression de l'emprunt
    $query = $dbu->prepare("DELETE FROM emprunt WHERE id=? ;");
    $query->execute(array($book['id']));

    //Ajout de l'exemplaire
    $query = $dbu->prepare("SELECT disponible FROM livres WHERE id = ? ;");
    $query->execute(array($book['id_livre']));

    $nb = $query->fetch();

    $query = $dbu->prepare("UPDATE livres SET disponible = ? WHERE id = ? ;");
    $query->execute(array($nb['disponible']+1, $book['id_livre']));

}

if(isset($_POST['voir_reserv'])){
    echo "Voici les réservation de l'utilisateur ".$_POST['uID'];

    echo "<table>
        <thead>
        <tr>
            <th scope=\"col\">titre</th>
            <th scope=\"col\">auteur</th>
            <th scope=\"col\">editeur</th>
        </tr>
        </thead>
        <tbody>";


    $query = $dbu->prepare('SELECT * FROM reservation WHERE id_user LIKE ?');
    $query->execute(array($_POST['uID']));;

    while ($row = $query->fetch()) {
        echo "<tr>
            <td>" . $row["titre"] . "</td>
            <td>" . $row["auteur"] . "</td>
            <td>" . $row["editeur"] . "</td>
            <td><form method='post' action='gestemprunts.php'><button type='submit' name='emprunt' value='" . serialize($row) . "'>Emprunt</button></form></td>";
    }
    echo "</tr>
        </tbody>
        </table>";
}
if(isset($_POST['emprunt'])){
    $book = unserialize($_POST['emprunt']);
    $date = date('d/m/Y',time()+3600*24*31);

    //Vérification du nombre d'exemplaire
    $query = $dbu->prepare("SELECT disponible FROM livres WHERE id = ? ;");
    $query->execute(array($book['id_book']));

    $nb = $query->fetch();

    if($nb['disponible']!=0){
        echo $nb;
        //Suppression d'un exemplaire
        $query = $dbu->prepare("UPDATE livres SET disponible = ? WHERE id = ? ;");
        $query->execute(array($nb['disponible']-1, $book['id_book']));

        //Emprunt d'un livre
        $query = $dbu->prepare("INSERT INTO emprunt (id_livre,id_user,echeance,titre) VALUES (?,?,?,?) ;");
        $query->execute(array($book['id_book'],$book['id_user'], $date ,$book['titre']));

        //Suppression de la réservation
        $query = $dbu->prepare("DELETE FROM reservation WHERE id=? ;");
        $query->execute(array($book['id']));
    }
    else{
        echo '<script type="text/javascript">
        alert("Emprunt impossible, aucun exemplaire disponible");
        </script>';
    }
}





addFooter();

function addHeader(){
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Gestion des emprunts</title>
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
function topnavadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a href="addpage.php">Ajouter Livre</a>
      <a href="gestusers.php">Gérer utilisateurs</a>
      <a class="active" href="gestemprunts.php">Gérer emprunts</a>
      <a href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}
?>