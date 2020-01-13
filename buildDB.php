<?php
/**
 * Created by PhpStorm.
 * User: CTFla
 * Date: 26/03/2019
 * Time: 17:16
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

$dbu = connexpdo();

$create = 'CREATE TABLE IF NOT EXISTS reservation (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              id_book INTEGER,
              titre VARCHAR(50),
              auteur VARCHAR(50),
              editeur VARCHAR(50),
              utilisateur VARCHAR(50)
)';

$dbu->exec($create);