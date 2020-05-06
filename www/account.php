<style>
    <?php include 'style.css';?>
</style>
<body class="body">
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

        $servername = "127.0.0.1";
        $usernameServer = "interns2020";
        $passwordServer = "interns2020";
        $dbname = "internDatabase";

    if ($password != $cpassword) {
        echo "Passwords do not match";
    }
    // Create connection
    $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);
    $password = password_hash($password, PASSWORD_DEFAULT);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT username FROM internDatabase.users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows <= 0) {
        $sql = "INSERT INTO internDatabase.users (username, password, email, wins) VALUES ('$username', '$password', '$email', 0)";
        $conn->query($sql);
        echo "Account created! Click here to login <a href = \"index.php\">Login.</a>";

    } else {
        echo "Please choose another username";
    }
    $conn->close();
    }
    if (isset($_GET['click'])) {
        newUser();
    }
    ?>
    <p> Already have an account? <a href = "index.php">Login.</a></p>

</div>
</body>
