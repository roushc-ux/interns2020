<?php session_start(); ?>
<style>
<?php include 'style.css';?>
</style>
<body class="body">

    <div class="page-wrap">
        <div class="header">Welcome to LAZ Blackjack!</div>
        <h1>Log in to play!</h1>
        <p></p>
        <form method="get" class="form" id="loginForm" action="/index.php">
            <label for="uname">Username: </label>
            <input type="text" id = "uname" name="uname"><br><br>
            <label for="password">Password: </label>
            <input type="text" id = "password" name="password"><br><br>
            <input type="submit" name="click" value = "Login">
        </form>
        <p>Don't have an account? <a href = "account.php">Sign up</a></p>
        <?php
        function login() {

            //Get Username and password
        $username = $_GET["uname"];
        $password = $_GET["password"];
        //Sanitize
        $username = stripcslashes($username);
        $password = stripcslashes($password);

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

        $sql = "SELECT username FROM internDatabase.users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows <= 0) {
            echo "Account not found";
        } else {
            $sql = "SELECT password FROM internDatabase.users WHERE username = '$username'";
            $result = $conn->query($sql);
            while($row = mysqli_fetch_assoc($result)) {
                if(password_verify($password, $row["password"])) {
                    $sql = "INSERT INTO internDatabase.onlineUsers (username) VALUES ('$username')";
                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['loggedin'] = True;
                        $_SESSION['login_user'] = $username;
                        echo "<a href = 'lobby.php'> Continue to Game Lobby</a>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
                else {
                    echo "Wrong Password";
                }
            }

        }
        $conn->close();
        }
        if (isset($_GET['click'])){
            login();
        }

        function logout() {
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

            $user = $_SESSION['login_user'];
            $sql = "DELETE FROM internDatabase.onlineUsers WHERE username = '$user'";
            $conn->query($sql);

            session_destroy();
            session_unset();
            unset($_SESSION["loggedin"]);
            $_SESSION = array();
        }

        if (isset($_GET['logout'])){

            logout();
        }
        ?>


    </div>
    <script type="text/javascript" src="deck.js"></script>
    <script type="text/javascript" src="player.js"></script>
</body>
