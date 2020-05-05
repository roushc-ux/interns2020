<style>
    <?php include 'style.css';?>
</style>
<body>
<div class="page-wrap">
    <div class="header">Create New Account </div>
    <form method="post" class="form" id="newUser" action="/account.php">
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
    <a href = "index.php"> Back</a>
    <?php
    function newUser() {
    $username = $_GET["uname"];
    $password = $_GET["password"];
    $email = $_GET["email"];
    $cpassword = $_GET["cpassword"];

    $servername = "localhost";
    $usernameServer = "username";
    $passwordServer = "password";

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
    } else {
        echo "Please choose another username";
    }
    $conn->close();
    }
    if (isset($_POST['click'])) {
        newUser();
    }
    ?>

</div>
</body>
