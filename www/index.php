<?php session_start();
include 'database.php';?>
<style>
<?php include 'style.css';?>
</style>
<body class="body">

    <div class="page-wrap">
        <div class="header">Welcome to LAZ Blackjack!</div>
        <h1>Log in to play!</h1>
        <p></p>
        <form method="get" class="form" id="loginForm" action="/index.php">
            <label for="username">Username: </label>
            <input type="text" id = "username" name="username" required><br><br>
            <label for="password">Password: </label>
            <input type="password" id = "password" name="password" required><br><br>
            <input type="submit" name="click" value = "Login">
        </form>
        <p>Don't have an account? <a href = "account.php" class = "link">Sign up</a></p>

        <?php
        function login() {
            // Get username and password
            $username = $_GET["username"];
            $password = $_GET["password"];
            // Sanitize
            $username = stripcslashes($username);
            $password = stripcslashes($password);

            $result = selectResult('user', 'username', 'username', $username);

            // Checking to see if the account is found using DB
            if ($result->num_rows <= 0) {
                echo "Incorrect username or password";
                return;
            } else { //Matching password to username
                $result = selectResult('user','password', 'username', $username);
                while($row = mysqli_fetch_assoc($result)) {
                    if(password_verify($password, $row["password"])) { //Password verify function
                        //Updates server to add online User if not already online
                        $result = selectResult('user', 'username', 'username', $username);
                        if ($result->num_rows == 0) {
                            insert('online_user', 'username', $username);
                        }
//                        // Unset all prev session vars on the computer (between tabs) just in case
                        unset($_SESSION['sessionHandID']);
                        unset($_SESSION['sessionDeckID']);
                        unset($_SESSION['sessionPlayer']);
                        unset($_SESSION['is_btn_disabled']);
                        unset($_SESSION['active_time']);

                        $_SESSION['loggedin'] = True;
                        $_SESSION['login_user'] = $username; //Updates session for logged in user
                        echo "<script> document.location.href='/lobby.php'</script>";

                    }
                    else { //Non-matching password
                        echo "Incorrect username or password";
                        return;
                    }
                }
            }
        }
        if (isset($_GET['click'])){
            login();
        }
    ?>

    </div>
<script type="text/javascript" src="deck.js"></script>
<script type="text/javascript" src="player.js"></script>
</body>
