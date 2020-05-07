<?php
include 'helper.php';
$conn = makeConnection();
$sql = "SELECT numPlayers FROM games WHERE gameID = 1 LIMIT 1";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
$players = $row["numPlayers"];
echo "$players";
?>