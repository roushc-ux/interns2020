<?php
session_start();
include 'game_ui.php';
initSession();
startGame();
?>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="game2page.js"></script>
    <meta http-equiv="refresh" content="3">
    <title>Game</title>
</head>
<style>
    <?php include 'style.css'; ?>
</style>
<body class="game" >
<div class="game-wrap">
    <div id="gameStart"><b>Waiting for 3 players before game begins.</b></div>
    <div id="currentPlayer"><b>You are not current player</b></div>

    <!--        <form method="post">-->
    <!--            <input id="newRoundBtn" type="submit" name="newRound" value="New Round">-->
    <!--        </form>-->

    <!-- Dealer -->
    <div class="buttons-box" style="margin-bottom: 5rem;">
        <div class="player">
            <div class="card-box"><?php dealerHand(); ?></div>
            <div class="card">Dealer</div>
        </div>
    </div>

    <div class="buttons-box">
        <!-- Main player -->
        <div class="player">
            <div class="card-box">
                <?php
                mainPlayerHand();
                $playerID = getPlayerID();
                ?>
            </div>
            <div class='card'><?php echo $_SESSION['login_user'] ?></div>
        </div>

        <!-- Other players -->
        <?php otherPlayerHand(); ?>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <form method="post">
                <input id="hitBtn" type="submit" name="hit" value="Hit">
                <input id="stayBtn" type="submit" name="stay" value="Stay">
                <input id="newRound" type="submit" name="newRound" value="New Round">
                <!--                    <input type="submit" name="reset" value="Reset">-->
            </form>
        </div>
    </div>
</div>
</body>

<script>
    //each client will loop until 3 players
    checkPlayers();
    //console.log("checkStart has started");

    //each client will loop until it is active player
    checkCurrentPlayer(<?php echo $playerID?>); // this way checks when page first load
    //let checkCurrent = setInterval(function() { checkCurrentPlayer(<?php //echo $playerID?>//) }, 3000);
</script>