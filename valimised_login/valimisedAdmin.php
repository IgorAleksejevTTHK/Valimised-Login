<?php
require ('config.php');
//+1 punkt
global $yhendus;
if(isset($_REQUEST["punktidnulliks"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid=0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["punktidnulliks"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]"); //adressiriba puhastab päring ja jääb faili nimi
    $yhendus->close();

}
if(isset($_REQUEST["presidentNimi"]) && !empty($_REQUEST["presidentNimi"])) {
    
    $paring=$yhendus->prepare("INSERT INTO valimised(president,pilt, lisamisaeg, avalik) values(?,?, NOW(),?)");
    $paring->bind_param("ssi", $_REQUEST["presidentNimi"], $_REQUEST["pilt"], $_REQUEST["avalik"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["naita"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET avalik=1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["naita"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["peida"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET avalik=0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["peida"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["kustuta"])) {
    $paring = $yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["delete_komment_id"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid='' WHERE id=?");
    $paring->bind_param("i", $_REQUEST["delete_komment_id"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: login.php');
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
<h1>Admin </h1>
<nav>
    <ul>

        <li><a href="valimisedAdmin.php">Admin leht</a></li>
    </ul>
</nav>


<table>
    <tr>
        <th>Nimi</th>
        <th>Pilt</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>Kommentaarid</th>
        <th>Haldus</th>


    </tr>
    <?php
    global $yhendus;
    $paring=$yhendus->prepare("SELECT id, president, pilt, punktid, lisamisaeg, avalik, kommentaarid FROM valimised");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $avalik, $kommentaarid); ;
    $paring->execute();
    while($paring->fetch()){
        echo "<tr>";
        echo "<td>$president</td>";
        echo "<td><img src='$pilt' alt='pilt'></td>";
        echo "<td>$punktid</td>";
        echo "<td>$lisamisaeg</td>";
        echo "<td>" .nl2br( htmlspecialchars($kommentaarid) ). "</td>";
        echo "<td><a href='?punktidnulliks=$id'>Punktid nulliks</a></td>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";

        echo "<td><a href='?delete_komment_id=$id'>Kustuta kommentaari</a></td>";
        $tekst="Näita";
        $seisund="peida";
        $tekstiLehel="Näidatud";
        if($avalik==0){
            $tekstiLehel="Peidetud";
            $seisund='naita';
            $tekst='Peida';

        }
        echo "<td><a href='?$seisund=$id'>$tekst</a></td>";
        echo "<td>$tekstiLehel</td>";

        echo "</tr>";
    }
    /* ADMIN:
    1.delete kandidaat
    2.punktid nulliks
    3.ei saa +1/-1 punkt
    4.admin kohe saab lisada avalikuse staatus
    */
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
    <label for="avalik">Avalik</label>
    <input type="checkbox" name="avalik" id="avalik" value="1">

    <br>
    <input type="submit" value="Lisa president">

</form>
</body>
<form action="logout.php" method="post">
    <input type="submit" name="logout" value="Logi välja">
</form>

</html>
