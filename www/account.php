<style>
    <?php include 'style.css';?>
</style>
<body>
<div class="page-wrap">
    <div class="header">Create New Account </div>
    <p></p>
    <form method="get" class="form" id="newUser" action="/account.php">
        <label for="uname">New Username: </label>
        <input type="text" id = "uname" name="uname"><br><br>
        <label for="password">New Password: </label>
        <input type="text" id = "password" name="password"><br><br>
        <label for="cpassword">Confirm Password: </label>
        <input type="text" id = "cpassword" name="cpassword"><br><br>
        <label for="email">New Email: </label>
        <input type="text" id = "email" name="email"><br><br>
        <input type="submit" name="click" value = "Create New Account">
    </form>
    <p>Already have an account? <a href = "index.php">Login.</a></p>
    <?php
    function newUser() {
    $username = $_GET["uname"];
    $password = $_GET["password"];
    $email = $_GET["email"];
    $cpassword = $_GET["cpassword"];

    //Sanitize
        $username = stripcslashes($username);
        $password = stripcslashes($password);
        $email = stripcslashes($email);
        $cpassword = stripcslashes($cpassword);

    $servername = "localhost";
    $usernameServer = "root";
    $passwordServer = "140Learning";

    if ($password != $cpassword) {
        echo "Passwords do not match";
    }
    // Create connection
    $conn = new mysqli($servername, $usernameServer, $passwordServer);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT username FROM users WHERE username = $username";
    $result = $conn->query($sql);

    if ($result->num_rows <= 0) {
        $sql = "INSERT INTO users (username, password, email) VALUES ($username, $password, $email)";
        $conn->query($sql);
        $_SESSION['loggedin'] = True;
        $_SESSION['login_user'] = $username;
        echo "<a href = 'lobby.php'> Account Created! Continue to Game Lobby</a>";
    } else {
        echo "Please choose another username";
    }
    $conn->close();
    }
    if (isset($_GET['click'])) {
        newUser();
    }
    ?>

</div>
</body>
