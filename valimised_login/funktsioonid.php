<?php
require ('config.php');
global $yhendus;

//lisa 1 punkt
function lisapunkt($id){
    global $yhendus;
        $paring = $yhendus->prepare("UPDATE valimised SET punktid=punktid+1 WHERE id=?");
        $paring->bind_param("i", $id);
        $paring->execute();
        $yhendus->close();

}
function naitatabel()
{
    global $yhendus;
    $paring = $yhendus->prepare("SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid from valimised WHERE avalik=1 or avalik=0");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $kommentaarid);
    $paring->execute();
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>{$president}</td>";
        echo "<td>{$punktid}</td>";
        echo "<td>{$kommentaarid}</td>";
        echo "<td><a href='?lisa1punkt={$id}'>+1 punkt</a></td>";
        echo "<td><a href='?vota1punkt={$id}'>-1 punkt</a></td>";
        echo "<td><a href='?kustutapresident={$id}'>Kustuta</a></td>";
        echo "<td><a href='?naita={$id}'>Naita</a></td>";
        echo "<td><a href='?peida={$id}'>Peida</a></td>";
        echo "<td><a href='?delete_komment_id={$id}'>Kustuta kommentaar</a></td>";
        echo "<td>
<form method='post' action=''>
    <input type='hidden' name='uue_komment_id' value='$id'>
    <input type='text' name='uus_kommentaar' id='uus_kommentaar'>
    <input type='submit' value='ok'>
</form>
</td>";
        echo "</tr>";
        echo "</tr>";
    }
}
// uue presidenti lisamine INSERT
function lisapresident($presidentNimi,$pilt, $punktid){
    global $yhendus;
    $paring=$yhendus->prepare("INSERT INTO valimised(president,pilt, lisamisaeg, punktid) values(?,?, NOW(),?)");
    $paring->bind_param("ssi", $presidentNimi,$pilt, $punktid);
    $paring->execute();
    $yhendus->close();
}
//kustutamine
function kustutapresident($id){
    global $yhendus;
    $paring = $yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $paring->bind_param("i", $id);
    $paring->execute();
    $yhendus->close();
}
function lisakommentaar(){
    global $yhendus;
        $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid=concat(kommentaarid, ?) WHERE id=?");
        $komment2="\n".$_REQUEST['uus_kommentaar']."\n";
        $paring->bind_param("si",$komment2, $_REQUEST["uue_komment_id"]);
        $paring->execute();
        $yhendus->close();

}

function naita($id){
    global $yhendus;
        $paring = $yhendus->prepare("UPDATE valimised SET avalik=1 WHERE id=?");
        $paring->bind_param("i", $_REQUEST["naita"]);
        $paring->execute();
        $yhendus->close();
}

function peida($id){
    global $yhendus;
        $paring = $yhendus->prepare("UPDATE valimised SET avalik=0 WHERE id=?");
        $paring->bind_param("i", $_REQUEST["peida"]);
        $paring->execute();
        $yhendus->close();

}
function vota1punkt($id){
    global $yhendus;
    $paring = $yhendus->prepare("UPDATE valimised SET punktid=punktid-1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["vota1punkt"]);
    $paring->execute();
    $yhendus->close();
}
function kustutakommentaar($id){
    global $yhendus;
    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid='' WHERE id=?");
    $paring->bind_param("i", $id);
    $paring->execute();
    $paring->close();
}
