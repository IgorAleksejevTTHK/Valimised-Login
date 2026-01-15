<?php
require('config.php');
global $yhendus;



if(isset($_GET["lisa1punkt"])) {

    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid + 1 WHERE id=?");
    $paring->bind_param("i", $_GET["lisa1punkt"]);
    $paring->execute();

    header("Location: ?id=" . $_GET["lisa1punkt"]);
    exit();
}



if(isset($_GET["vota1punkt"])) {

    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid - 1 WHERE id=?");
    $paring->bind_param("i", $_GET["vota1punkt"]);
    $paring->execute();

    header("Location: ?id=" . $_GET["vota1punkt"]);
    exit();
}



if(isset($_POST["uue_komment_id"])) {

    $paring = $yhendus->prepare("
        UPDATE valimised 
        SET kommentaarid = CONCAT(kommentaarid, ?) 
        WHERE id = ?
    ");

    $komment2 = "\n" . $_POST['uus_kommentaar'] . "\n";
    $paring->bind_param("si", $komment2, $_POST["uue_komment_id"]);
    $paring->execute();

    header("Location: ?id=" . $_POST["uue_komment_id"]);
    exit();
}



if(isset($_GET["delete_komment_id"])) {

    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid='' WHERE id=?");
    $paring->bind_param("i", $_GET["delete_komment_id"]);
    $paring->execute();

    header("Location: ?id=" . $_GET["delete_komment_id"]);
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Valimised</title>
    <link rel="stylesheet" type="text/css" href="galstyle.css">
</head>
<body>

<div id="menyykiht">
    <h2>Kandidaadid</h2>
    <ul>
        <?php
        $kask = $yhendus->prepare("SELECT id, pilt FROM valimised");
        $kask->bind_result($id, $pilt);
        $kask->execute();

        while ($kask->fetch()) {
            echo "
                <li>
                    <a href='?id=$id'>
                        <img class='thumb' src='$pilt' alt='pilt'>
                    </a>
                </li>
            ";
        }
        ?>
    </ul>
</div>

<div id="sisukiht">
    <?php
    if(isset($_GET["id"])){

        $kask = $yhendus->prepare("
            SELECT id, president, punktid, pilt, kommentaarid, lisamisaeg
            FROM valimised
            WHERE id=?
        ");

        $kask->bind_param("i", $_GET["id"]);
        $kask->bind_result($id, $president, $punktid, $pilt, $kommentaarid, $lisamisaeg);
        $kask->execute();

        if($kask->fetch()){
            echo "<h2>" . htmlspecialchars($president) . "</h2>";
            echo "<img class='bigimg' src='$pilt'><br><br>";

            echo "<b>Punktid:</b> " . htmlspecialchars($punktid) . "<br>";

            echo "<a href='?lisa1punkt=$id' class='btn'>+1 punkt</a> ";
            echo "<a href='?vota1punkt=$id' class='btn'>-1 punkt</a><br><br>";

            echo "<b>Kommentaarid:</b><br>";

            echo "<div class='comments-box'>";
            echo nl2br(htmlspecialchars($kommentaarid));
            echo "</div><br>";

            echo "<a class='delete-btn' href='?delete_komment_id=$id' onclick=\"return confirm('KUSTUTADA kõik kommentaarid?');\">
                    Kustuta kommentaarid
                  </a><br><br>";

            echo "<b>Lisamisaeg:</b> " . htmlspecialchars($lisamisaeg) . "<br><br>";
            ?>


            <form method="post" action="">
                <input type="hidden" name="uue_komment_id" value="<?php echo $id; ?>">
                <input type="text" name="uus_kommentaar" placeholder="Lisa kommentaar" required>
                <input type="submit" value="OK">
            </form>

            <?php
        }

    } else {
        echo "Tere tulemast! Vali menüüst president.";
    }
    ?>
</div>

</body>
</html>

<?php
$yhendus->close();
?>
