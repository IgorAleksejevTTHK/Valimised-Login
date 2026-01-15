<?php
require ('funktsioonid.php');
// päringud funktsioonide otsimiseks failis funktsioonid.php
if(isset($_REQUEST["lisa1punkt"])) {
    lisapunkt($_REQUEST["lisa1punkt"]);
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
//päring lisaPresident funktsiooni otsimiseks
if(isset($_REQUEST["presidentNimi"]) && !empty($_REQUEST["presidentNimi"])) {
    lisapresident($_REQUEST["presidentNimi"],$_REQUEST["pilt"],$_REQUEST["punktid"]);
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}

if (isset($_REQUEST["kustutapresident"])) {
    kustutapresident((int)$_REQUEST["kustutapresident"]);
    header("Location: ".$_SERVER["PHP_SELF"]);
    exit();
}
if (isset($_POST["uue_komment_id"]) && !empty($_POST["uus_kommentaar"])) {
    lisakommentaar();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if(isset($_REQUEST["naita"])) {
    naita((int)$_REQUEST["id"]);
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
if(isset($_REQUEST["peida"])) {
    peida((int)$_REQUEST["id"]);
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
if(isset($_REQUEST["vota1punkt"])) {
    vota1punkt($_REQUEST["vota1punkt"]);
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
if (isset($_REQUEST["delete_komment_id"])) {
    kustutakommentaar((int)$_REQUEST["delete_komment_id"]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tabel valimised funktsioonidega</title>
</head>
<body>
<h1>tabel valimised kirjutatud funktsioonide abil</h1>
<table>
    <tr>
        <th>Nimi</th>

        <th>Punktid</th>

        <th><Kommentaar></Kommentaar></th>

    </tr>
    <?php
    //funktsioon mis naitab tabeli asub funktsioonid.php failis
     naitatabel()
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
    <label for="punktid">Punktid:</label>
    <input type="number" name="punktid" id="punktid">
    <br>
    <input type="submit" value="Lisa president">

</form>

</body>
</html>
