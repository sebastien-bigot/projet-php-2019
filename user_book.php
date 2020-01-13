<?php
/**
 * Created by PhpStorm.
 * User: CTFla
 * Date: 27/03/2019
 * Time: 13:17
 *
 * Onglet pour utilisateur. Permet de voir les emprunts en cours et la date de retour prévu.
 */

session_start();
$dbu= new PDO("sqlite:users.db");

if(isset($_SESSION['uname'])){
    addHeader();
    if(isset($_SESSION['adm'])  ){
        topnavadmin();
    }
    else{
        topnavnotadmin();
    }

    echo "<p>Vous pouvez observer vos emprunts et réservation sur cette page</p>";


    echo "<p>Liste de vos réservations.</p>";
    echo "<table>
        <thead>
        <tr>
            <th scope=\"col\">titre</th>
            <th scope=\"col\">auteur</th>
            <th scope=\"col\">editeur</th>
        </tr>
        </thead>
        <tbody>";

    // Affichage des réservation
    $query = $dbu->prepare('SELECT * FROM reservation WHERE id_user LIKE ?');
    $query->execute(array($_SESSION['uname']));
    //$query = $dbu->prepare('SELECT * FROM reservation WHERE id_user LIKE ?');
    //$query->execute(array($_SESSION['id']));


    while ($row = $query->fetch()) {
        echo "<tr>
            <td>" . $row["titre"] . "</td>
            <td>" . $row["auteur"] . "</td>
            <td>" . $row["editeur"] . "</td>";
    }
    echo "</tr>
            </tbody>
            </table>";



    echo "<p>Liste de vos emprunts.</p>";
    echo "<table>
        <thead>
        <tr>
            <th scope=\"col\">titre</th>
            <th scope=\"col\">échéance</th>
        </tr>
        </thead>
        <tbody>";

    /* Affichage des emprunts
    $query = $dbu->prepare('SELECT * FROM emprunt WHERE utilisateur LIKE ?');
    $query->execute(array($_SESSION['uname']));*/
    $query = $dbu->prepare('SELECT * FROM emprunt WHERE id_user LIKE ?');
    $query->execute(array($_SESSION['uname']));


    while ($row = $query->fetch()) {
        echo "<tr>
            <td>" . $row["titre"] . "</td>
            <td>" . $row["echeance"] . "</td>";
    }
    echo "</tr>
            </tbody>
            </table>";

    addFooter();
}
else {
    echo '<script type="text/javascript">
    alert("Vous n\'êtes pas connecté");
    window.location.replace("login.php");
    </script>';
}





function topnavnotadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a class="active" href="user_book.php">Mes livres</a>
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