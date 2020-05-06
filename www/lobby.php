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
        <input type="submit" name="logout" value = "Logout" > <!-- logout button-->
    </form>

    <div class="header">Game Lobby </div>
    <a href="game1page.php">Enter Game</a>
</div>
</body>