<style>
    <?php include 'style.css';?>
</style>
<body>
<div class="page-wrap">
    <div class="header">Create New Account </div>
    <form class="form" id="newUser" action="/action_page.php">
        <label for="uname">New Username: </label>
        <input type="text" id = "uname" name="uname"><br><br>
        <label for="password">New Password: </label>
        <input type="text" id = "password" name="password"><br><br>
        <label for="email">New Email: </label>
        <input type="text" id = "email" name="email"><br><br>
        <input type="button" onclick="newUser()" value = "Create New Account">
    </form>
    <?php

    $username = $_GET["uname"];
    $password = $_GET["password"];
    $email = $_GET["email"];

    $servername = "localhost";
    $usernameServer = "username";
    $passwordServer = "password";

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
        $_SESSION['loggedin'] = True;
        $_SESSION['login_user'] = $username;
    } else {
        echo "Please choose another username";
    }
    $conn->close();
    ?>
    <a href = "index.php"> Back</a>
</div>
</body>
