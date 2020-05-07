<?php session_start(); ?>
<style>
    <?php include 'style.css';?>
</style>
<body class="lobby">
<div class="page-wrap">
    <div> Hi <?php

         echo $_SESSION['login_user']?> </div>
    <div> You have <?php //setting up the server
        $servername = "localhost";
        $usernameServer = "interns2020";
        $passwordServer = "interns2020";
        $dbname = "internDatabase";

        // Create connection
        $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $user = $_SESSION['login_user'];
        $sql = "SELECT wins FROM internDatabase.users WHERE username = '$user'";
        $result = $conn->query($sql);
        while($row = mysqli_fetch_assoc($result)) {
            echo $row['wins']; //showing the user their total amount of wins
        }?> wins. Wanna win more?</div>
    <form method="get" class="logout" id="loginForm" action="/index.php">
        <input type="submit" name="logout" value = "Logout" >
    </form>
    <form method="get" class="delAccount" id="deleteForm" action="/lobby.php">
        <input type="submit" name="delAccount" value = "Delete Account" >
    </form>

    <div class="header">Game Lobby </div>
    <a href="game2page.php">Enter Game</a>


    <?php
    // Permanently deletes a user's account
    function deleteAccount() {
        $confirm = "<script> window.confirm('Are you sure you want to delete your account?');</script>";
        if ($confirm) {
            $servername = "127.0.0.1";
            $usernameServer = "interns2020";
            $passwordServer = "interns2020";
            $dbname = "internDatabase";

            // Create connection
            $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // logout the user
            $user = $_SESSION['login_user'];
            $sql = "DELETE FROM internDatabase.onlineUsers WHERE username = '$user'";
            $conn->query($sql);

            //Destroying user's session
            session_destroy();
            session_unset();
            unset($_SESSION["loggedin"]);
            $_SESSION = array();

            // remove user from db
            $sql = "DELETE FROM internDatabase.users WHERE username = '$user'";
            $conn->query($sql);

            echo "<script> document.location.href='/index.php'</script>";
        }
    }

    if (isset($_GET['delAccount'])){
        deleteAccount();
    }
    ?>
</div>
</body>