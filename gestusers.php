<?php
/**
 * Created by PhpStorm.
 * User: Seb
 * Date: 26/03/2019
 * Time: 03:26
 */

session_start();
$db= new PDO("sqlite:users.db");

$SALT="HODEEiOJFHdIirYURYHFGGT528963HGFH";
if(isset($_SESSION['adm'])){
    addHeader();
    topnavadmin();
}
else{
    echo '<script type="text/javascript">
    alert("Accès refusé");
    window.location.replace("login.php");
    </script>';
}
if(isset($_POST['createacc'])){
    if(isset($_POST['isadm']))
        $booladm=1;
    else
        $booladm=0;

    $query= $db->prepare("SELECT username FROM users WHERE username=?");
    $query->execute(array($_POST['uname']));
    //Password confimation & mirror username verification
    if($_POST['pwd']==$_POST['c_pwd'] && !($res = $query->fetch())){
        $cmdp=crypt($_POST['pwd'],$SALT);
        $query2= $db->prepare("INSERT into users (username, password, admin,desactive,mail,tel)
                  VALUES 
                  (?,?,?,?,?,?)");
        $query2->execute(array($_POST['uname'],$cmdp,$booladm,0,$_POST['mail'],$_POST['tel']));
        echo '<script type="text/javascript">
        alert("Compté créé!");
        </script>';

    }
    else{
        echo "Echec de la création du compte.\n";
        if($_POST['pwd']!=$_POST['c_pwd']){echo "Votre confirmation de mot de passe est invalide";}
        else{echo "Votre nom d'utilisateur est déjà utilisé";}

    }
}

if(isset($_POST["delete"])){
    $req = $db->prepare("DELETE FROM users WHERE username=?");
    $res = $req->execute(array($_POST['delete']));
    if(!$res) {
        echo "erreur suppression";
    }
}

if(isset($_POST["activate"])){
    $req = $db->prepare("UPDATE users SET desactive=0 WHERE username=?");
    $res = $req->execute(array($_POST['activate']));
    if(!$res) {
        echo "erreur activation";
    }
}

if(isset($_POST["deactivate"])){
    $req = $db->prepare("UPDATE users SET desactive=1 WHERE username=?");
    $res = $req->execute(array($_POST['deactivate']));
    if(!$res) {
        echo "erreur desactivation";
    }
}
?>
<form method="post" action="gestusers.php">
    <p>Ajouter un compte:</p>
    <label>Nom de compte<input type="text" id="uname" name="uname" required></label><br>
    <label>Mot de Passe<input type="password" id="pwd" name="pwd" required></label><br>
    <label>Confirmez le Mot de Passe<input type="password" id="c_pwd" name="c_pwd" required></label><br>
    <label>Statut administrateur<input type="checkbox" id="isadm" name="isadm"></label><br>
    <label>Mail<input type="text" id="mail" name="mail" required></label><br>
    <label>Tel<input type="text" id="tel" name="tel" required></label><br>
    <label>Create account<input type="submit" id="createacc" name="createacc"></label><br>
</form>
<br><br>

<table>
    <thead>
    <tr>
        <th scope="col">username</th>
        <th scope="col">is_admin</th>
        <th scope="col">activer</th>
        <th scope="col">desactiver</th>
        <th scope="col">supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $result = $db->query("SELECT username,admin,desactive FROM users");

    while($row = $result->fetch()){
        echo "<tr>";
        echo "<th scope=\"row\">".$row["username"]."</th>";
        echo "<td>".$row["admin"]."</td>";
        if($row["username"]==$_SESSION['uname']){
            echo "<td><form method='post' action='gestusers.php'><button type='submit' name='activate' value='" . $row["username"] . "' disabled>Activer</button></form></td>";
            echo "<td><form method='post' action='gestusers.php'><button type='submit' name='deactivate' value='" . $row["username"] . "' disabled>Désactiver</button></form></td>";
            echo "<td><form method='post' action='gestusers.php'><button type='submit' name='delete' value='" .$row["username"]."' disabled>Supprimer</button></form></td>";
        }
        else {
            if ($row['desactive'] == 0) {
                echo "<td><form method='post' action='gestusers.php'><button type='submit' name='activate' value='" . $row["username"] . "' disabled>Activer</button></form></td>";
                echo "<td><form method='post' action='gestusers.php'><button type='submit' name='deactivate' value='" . $row["username"] . "'>Désactiver</button></form></td>";
            } else {
                echo "<td><form method='post' action='gestusers.php'><button type='submit' name='activate' value='" . $row["username"] . "'>Activer</button></form></td>";
                echo "<td><form method='post' action='gestusers.php'><button type='submit' name='deactivate' value='" . $row["username"] . "' disabled>Désactiver</button></form></td>";
            }
            echo "<td><form method='post' action='gestusers.php'><button type='submit' name='delete' value='" . $row["username"] . "'>Supprimer</button></form></td>";
        }
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
<?php
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
function topnavadmin(){
    echo ' <div class="topnav">
      <a href="mainpage.php">Recherche</a>
      <a href="addpage.php">Ajouter Livre</a>
      <a class="active" href="gestusers.php">Gérer utilisateurs</a>
      <a href="gestemprunts.php">Gérer emprunts</a>
      <a href="settings.php">Paramètres</a>
      <a href="login.php">Se déconnecter</a>
    </div> ';
}
?>