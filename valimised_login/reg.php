<?php
require('config.php');
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $pass  = trim($_POST['pass']);
    $pass2 = trim($_POST['pass2']); // подтверждение

    $sool = 'taiestisuvalinetekst'; // та же соль, что и в login.php

    if (empty($login) || empty($pass) || empty($pass2)) {
        $errors[] = "Kõik väljad peavad olema täidetud.";
    } elseif ($pass !== $pass2) {
        $errors[] = "Paroolid ei kattu.";
    } else {
        // Проверяем, есть ли такой пользователь
        $stmt = $yhendus->prepare("SELECT id FROM kasutajad WHERE kasutaja=?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Selline kasutaja juba olemas.";
        } else {
            // Хэшируем пароль через crypt()
            $kryp = crypt($pass, $sool);

            $stmt = $yhendus->prepare("INSERT INTO kasutajad (kasutaja, parool) VALUES (?, ?)");
            $stmt->bind_param("ss", $login, $kryp);
            $stmt->execute();
            $stmt->close();

            $_SESSION['tuvastamine'] = $login; // сразу логиним
            header('Location: valimised.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registreeru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Registreeru</h1>

<?php
if (!empty($errors)) {
    echo "<ul style='color:red;'>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}
?>

<form action="" method="post">
    <label for="login">Kasutajanimi:</label><br>
    <input type="text" name="login" id="login"><br><br>

    <label for="pass">Parool:</label><br>
    <input type="password" name="pass" id="pass"><br><br>

    <label for="pass2">Kinnita parool:</label><br>
    <input type="password" name="pass2" id="pass2"><br><br>

    <input type="submit" value="Registreeru">
    <p>Juba konto olemas? <a href="login.php">Logi sisse</a></p>
</form>


</body>
</html>
