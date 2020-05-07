<?php session_start();?>
<?php
include 'helper.php';
$conn = makeConnection();
$gameID = $_SESSION["gameID"];
$sql = "SELECT playerTurn FROM games WHERE $gameID = gameID LIMIT 1";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
$playerTurn = $row["playerTurn"];
echo "$playerTurn";
?>