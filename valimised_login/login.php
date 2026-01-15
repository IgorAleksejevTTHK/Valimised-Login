<?php
global $yhendus;
session_start();
require('config.php');

if (isset($_SESSION['tuvastamine'])) {
    if ($_SESSION['tuvastamine'] == 'admin') {
        header('Location: valimisedAdmin.php');
    } else {
        header('Location: valimised.php');
    }
    exit();
}

if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    $login = trim($_POST['login']);
    $pass  = trim($_POST['pass']);

    $stmt = $yhendus->prepare("SELECT parool FROM kasutajad WHERE kasutaja=?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hash);

    if ($stmt->num_rows === 1) {
        $stmt->fetch();
        if (password_verify($pass, $hash)) {
            $_SESSION['tuvastamine'] = $login;
            if ($login === 'admin') {
                header('Location: valimisedAdmin.php');
            } else {
                header('Location: valimised.php');
            }
            exit();
        } else {
            echo "<p style='color:red;'>Vale kasutaja või parool</p>";
        }
    } else {
        echo "<p style='color:red;'>Vale kasutaja või parool</p>";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Valimiste leht</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<h1>Presidendi valimised</h1>
<nav>
    <ul>
        <li><a href="valimised.php">Kasutaja leht</a></li>
        <li><a href="valimisedAdmin.php">Admin leht</a></li>
    </ul>
</nav>
<h1>Login</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Logi sisse">
    <p>Pole kontot? <a href="reg.php">Registreeri</a></p>
</form>

