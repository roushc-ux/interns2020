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
<script>
    //each client will loop until 3 players
    let checkStart = setInterval(checkPlayers, 3000);

    function checkPlayers() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let numPlayers = this.response;
                if (numPlayers == 3) {
                    document.getElementById("gameStart").innerHTML = "3 players have joined. Starting game.";
                    clearInterval(checkStart);
                }
            }
        }
        xmlhttp.open("GET", "getNumPlayers.php",true);
        xmlhttp.send();
    }
</script>
<body class="game">
<div id="gameStart"><b>Waiting for 3 players before game begins.</b></div>
<div class="page-wrap">
    <div class="header">Dealer</div>

    <div class="dealer-box">
        <div class="card" id = "dealerCard1"> J </div>
        <script type="text/javascript"> document.getElementById("dealerCard1").innerHTML = "something"</script>
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
