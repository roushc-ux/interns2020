<?php session_start();
if(!$_SESSION['login_user']) {
    header("Location: /index.php");
}
include 'database.php';?>
<style>
    <?php include 'style.css';?>
</style>
<body class="lobby">
<div class="page-wrap">
    <div>
        Hi <?php echo $_SESSION['login_user']; ?> !
    </div>
    <div> You have <?php
        $user = $_SESSION['login_user'];
        $row = select('user', 'wins', 'username', $user);
        $wins = $row['wins'];
        echo $wins; //showing the user their total amount of wins
        ?> wins. Wanna win more?
    </div>

    <form method="get" class="logout" id="loginForm" action="/lobby.php">
        <input type="submit" name="logout" value = "Logout" >
    </form>
    <form method="get" class="delAccount" id="deleteForm" action="/lobby.php">
        <input type="submit" name="delAccount" value = "Delete Account" >
    </form>

    <div class="header">Game Lobby </div>
    <a href="game2page.php">Enter Game</a>

    <?php
        function logout() { //Logout function

            $user = $_SESSION['login_user'];
            //Removing user from online user table in DB
            deleteFrom('online_user', 'username', $user);

            //Destroying user's session
            session_destroy();
            session_unset();
            unset($_SESSION["loggedin"]);
            // Unset all prev session vars on the computer
            unset($_SESSION['sessionHandID']);
            unset($_SESSION['sessionDeckID']);
            unset($_SESSION['sessionPlayer']);
            unset($_SESSION['is_btn_disabled']);
            unset($_SESSION['active_time']);

            $_SESSION = array();

            echo "<script> document.location.href='/index.php'</script>";
        }

        if (isset($_GET['logout'])){
            logout();
        }

        if (isset($_GET['delAccount'])){
            echo "<script> document.location.href='/deleteAccount.php'</script>";
        }
    ?>
</div>
</body>