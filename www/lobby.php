<?php session_start();
include 'helper.php';?>
<style>
    <?php include 'style.css';?>
</style>
<body class="lobby">
<div class="page-wrap">
    <div>
        Hi <?php echo $_SESSION['login_user']?>
    </div>
    <div> You have <?php //setting up the server

        // make connection to db
        $conn = makeConnection();

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
            $conn = makeConnection();

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $user = $_SESSION['login_user'];
            //Removing user from online user table in DB
            $sql = "DELETE FROM internDatabase.onlineUsers WHERE username = '$user'";
            $conn->query($sql);

            //Destroying user's session
            session_destroy();
            session_unset();
            unset($_SESSION["loggedin"]);
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