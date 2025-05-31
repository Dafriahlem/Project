<?php
session_start();


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'zonefit');



define('SITE_NAME', 'Zone Fit');
define('SITE_LANG', 'fr');
define('CURRENCY', 'DZD');


try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}


function redirect($url) {
    header("Location: $url");
    exit();
}


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function checkRole($role) {
    if (!isLoggedIn() || $_SESSION['user_role'] != $role) {
        redirect('login.php');
    }
}
?>