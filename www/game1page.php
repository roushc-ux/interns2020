<?php session_start();?>
<style>
    <?php include 'style.css';?>
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php
// get numPlayers, assign number to current player. Update numPlayers.
include 'database.php';
$conn = makeConnection();
$username = $_SESSION['login_user'];
echo $_SESSION['login_user'];
$sql = "SELECT gameID FROM blackjack.online_user WHERE username = '$username'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
if (!$row["gameID"]) {
    $sql = "UPDATE online_user SET gameID = 1 WHERE username = '$username'";
    $conn->query($sql);
    $sql = "SELECT numPlayers FROM game WHERE gameID = 1 LIMIT 1";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    $sql = "UPDATE online_user SET playerID = '$playerID' WHERE username = '$username'";
    $conn->query($sql);
    $newNumPlayers = $playerID + 1;
    $sql = "UPDATE game SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
    $conn->query($sql);
}
$sql = "SELECT playerID FROM online_user WHERE username = '$username'";
$result = $conn->query($sql);
$row = mysqli_fetch_array($result);
echo "playerId: " . $row['playerID'];
$playerID = $row['playerID'];

?>
<body class="game">
<div id="gameStart"><b>Waiting for 3 players before game begins.</b></div>
<div id="currentPlayer"><b>You are not current player</b></div>
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


<script>
    //each client will loop until 3 players
    let checkStart = setInterval(checkPlayers, 3000);
    console.log("checkStart has started");

    //jquery equivalent to make checkPlayers function cleaner
    function checkPlayers() {
        $.get('getNumPlayers.php', function(response) {
            let numPlayers = parseInt(this.response.match(/(\d+)/));
            console.log(typeof(numPlayers));
            console.log(numPlayers);
            if (numPlayers >= 3) {
                document.getElementById("gameStart").innerHTML = "3 players have joined. Starting game.";
                console.log("numPlayers is greater than 3");
                clearInterval(checkStart);
            }
        })
    }
    /* can remove when other function confirmed to work
    function checkPlayers() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let numPlayers = parseInt(this.response.match(/(\d+)/));
                console.log(typeof(numPlayers));
                console.log(numPlayers);
                if (numPlayers >= 3) {
                    document.getElementById("gameStart").innerHTML = "3 players have joined. Starting game.";
                    console.log("numPlayers is greater than 3");
                    clearInterval(checkStart);
                }
            }
        }
        xmlhttp.open("GET", "getNumPlayers.php", true);
        xmlhttp.send();
    }
    */

    //each client will loop until it is active player
    let checkCurrent = setInterval(checkCurrentPlayer, 3000);

    //jquery equivalent to make checkCurrentPlayer function cleaner
    function checkCurrentPlayer() {
        $.get('getCurrentPlayer.php', function(response) {
            let currentPlayer = this.response;
            if (currentPlayer == <?php echo $playerID?>) {
                document.getElementById("currentPlayer").innerHTML = "IT'S YOUR TURN";
            }
        })
    }

    /* can remove when other function confirmed to work
    function checkCurrentPlayer() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let currentPlayer = this.response;
                if (currentPlayer == <?php echo $_SESSION["playerID"]?>) {
                    document.getElementById("currentPlayer").innerHTML = "IT'S YOUR TURN";
                }
            }
        }
        xmlhttp.open("GET", "getCurrentPlayer.php", true);
        xmlhttp.send();
    }
    */

    //once client is the active player, poll the whole game status from server
    

</script>
</body>
