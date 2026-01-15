<?php
require ('config.php');
//+1 punkt
global $yhendus;
if(isset($_REQUEST["lisa1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid=punktid+1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]"); //adressiriba puhastab päring ja jääb faili nimi
    $yhendus->close();
}

if(isset($_REQUEST["vota1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid=punktid-1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["vota1punkt"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
//lisamine admetabelisse
if(isset($_REQUEST["presidentNimi"]) && !empty($_REQUEST["presidentNimi"])) {
    $paring=$yhendus->prepare("INSERT INTO valimised(president,pilt, lisamisaeg) values(?,?, NOW())");
    $paring->bind_param("ss", $_REQUEST["presidentNimi"], $_REQUEST["pilt"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}

//kommentaari lisamine
if(isset($_REQUEST["uue_komment_id"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid=concat(kommentaarid, ?) WHERE id=?");
    $komment2="\n".$_REQUEST['uus_kommentaar']."\n";
    $paring->bind_param("si",$komment2, $_REQUEST["uue_komment_id"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}



session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['tuvastamine'] == 'admin') {
    header('Location: valimisedAdmin.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Valimiste leht</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
		<h1>Presidendi valimised</h1>
        <nav>
            <ul>
                <li><a href="valimised.php">Kasutaja leht</a></li>
                <?php

                if ($_SESSION['tuvastamine'] === 'admin') {
                    echo '<li><a href="valimisedAdmin.php">Admin leht</a></li>';
                }
                ?>
            </ul>
        </nav>
<table>
    <tr>
        <th>Nimi</th>
        <th>Pilt</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>+1 punkt</th>
        <th>-1 punkt</th>
        <th>Kommentaarid</th>
    </tr>
    <?php
    global $yhendus;
    $paring=$yhendus->prepare("SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid from valimised WHERE avalik=1");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $kommentaarid);
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        echo "<td>$president</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>$punktid</td>";
        echo "<td>$lisamisaeg</td>";
        echo "<td><a href='?lisa1punkt=$id'>+1 punkt</a></td>";
        echo "<td><a href='?vota1punkt=$id'>-1 punkt</a></td>";
        echo "<td>" .nl2br( htmlspecialchars($kommentaarid) ). "</td>";
        echo "<td>
<form method='post' action=''>
    <input type='hidden' name='uue_komment_id' value='$id'>
    <input type='text' name='uus_kommentaar' id='uus_kommentaar'>
    <input type='submit' value='ok'>
</form>
</td>";
        echo "</tr>";

    }
    ?>
</table>
<h2>Lisa oma presidendi</h2>
        <form action="?">
            <label for="presidentNimi">President nimi: </label>
            <input type="text" name="presidentNimi" id="presidentNimi">
            <br>
            <label for="pilt">President pilt: </label>
            <textarea name="pilt" id="pilt"></textarea>
            <br>
            <input type="submit" value="Lisa president">

        </form>
</body>
</html>
<?php

