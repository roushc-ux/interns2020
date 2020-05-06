<?php session_start();?>
<style>
    <?php include 'style.css';?>
</style>
<?php
// get numPlayers, assign number to current player. Update numPlayers.
include 'helper.php';
$conn = makeConnection();
$sql = "SELECT numPlayers FROM games WHERE gameID = 1 LIMIT 1";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
$playerId = $row["numPlayers"];
echo "playerId: " . $playerId;
$newNumPlayers = $playerId + 1;
$sql = "UPDATE games SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
$conn->query($sql);
?>
<body class="game">
<div class="page-wrap">
    <div class="header">Dealer</div>

    <div class="dealer-box">
        <div class="card"> J </div>
        <div class="card"> F </div>
    </div>

    <div class="buttons-box">
        <div class="card-box">
            <div class="card"> J </div>
            <div class="card"> A </div>
        </div>
        <div class="card-box">
            <div class="card"> K </div>
            <div class="card"> A </div>
        </div>
        <div class="card-box">
            <div class="card"> 3 </div>
            <div class="card"> 6 </div>
        </div>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <div class="card">Player 1</div>
        </div>
        <div class="card-box">
            <div class="card">Player 2</div>
        </div>
        <div class="card-box">
            <div class="card">Player 3</div>
        </div>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <input type="submit" value="Hit">
            <input type="submit" value="Stay">
        </div>
        <div class="card-box">
            <input type="submit" value="Hit">
            <input type="submit" value="Stay">
        </div>
        <div class="card-box">
            <input type="submit" value="Hit">
            <input type="submit" value="Stay">
        </div>
    </div>
</div>
</body>
