<?php session_start(); 
include 'game_ui.php';
?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta http-equiv="refresh" content="5">
</head>
<style>
    <?php include 'style.css'; ?>
</style>
<body class="game">
<div class="page-wrap">
<!--    <form method="post">-->
<!--        <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">-->
<!--    </form>-->

    <div class="buttons-box">
        <!-- Main player -->
        <div class="player">
            <div class="card-box">
                <?php
                mainPlayerHand();
                ?>
            </div>

            <?php
                $username =  $_SESSION['login_user'];
                echo "<div class='card'>$username</div>";
            ?>
            
        </div>

        <!-- Other players -->
        <?php
        otherPlayerHand();
        ?>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <form method="post">
                <input id="hitBtn" type="submit" name="hit" value="Hit">
                <input id="stayBtn" type="submit" name="stay" value="Stay">
                <input type="submit" name="reset" value="Reset">
            </form>
        </div>
    </div>
</div>

<?php
    if (isset($_POST['addDeck'])) {
        addDeck();
    }
?>
</body>

function isLoginSessionExpired() {
    //giving them 30 seconds when it is
	$login_session_duration = 30; 
	$current_time = time(); 
    <script type = 'text/javascript'>
    var is_their_turn = document.getElementbyId('#hitBtn').enable;
    </script>
	if($is_their_turnand isset($_SESSION['loggedin_time']) and isset($_SESSION["user_id"])){  
		if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){ 
			return true; 
		} 
	}
	return false;
}

session_start();
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);
$url = "index.php";
if(isset($_GET["session_expired"])) {
	$url .= "?session_expired=" . $_GET["session_expired"];
}
header("Location:$url");

