<?php session_start();
include 'helper.php';?>
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
            // get username and password from submission
            $username = $_GET["uname"];
            $password = $_GET["password"];
            // sanitize
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $password = filter_var($password, FILTER_SANITIZE_STRING);

            // look for credentials in db
            $conn = makeConnection();
            $sql = "SELECT username FROM blackjack.user WHERE username = '$username'";
            $result = $conn->query($sql);

            // checking to see if the credentials are valid
            if ($result->num_rows <= 0) {
                echo "Incorrect username or password";
                return;
            } else { //Matching password to username
                $sql = "SELECT password FROM blackjack.user WHERE username = '$username'";
                $result = $conn->query($sql);
                while($row = mysqli_fetch_assoc($result)) {
                    if(password_verify($password, $row["password"])) { //Password verify function
                        //Updates server to add online User if not already online
                        $sql = "SELECT username FROM blackjack.online_user WHERE username = '$username'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {

                        }
                        else {
                            $sql = "INSERT INTO blackjack.online_user (username) VALUES ('$username')";
                            $conn->query($sql);
                        }

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
        $conn->close();
        }
        if (isset($_GET['click'])){
            login();
        }
    ?>

    </div>
<script type="text/javascript" src="deck.js"></script>
<script type="text/javascript" src="player.js"></script>
</body>
