<?php
include 'config.php';

try {
    $dbh = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
