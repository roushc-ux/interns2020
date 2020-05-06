<?php include 'helper.php';?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<style>
    <?php include 'style.css';?>
</style>
<body class="body">
<div class="page-wrap">
    <div class="header">Create New Account </div>
    <p></p>
    <form method="get" class="form" id="newUser" action="/account.php">
        <label for="uname">New Username: </label>
        <input type="text" id = "uname" name="uname" required><br><br>
        <label for="password">New Password: </label>
        <input type="password" id = "password" name="password" required><br><br>
        <label for="cpassword">Confirm Password: </label>
        <input type="password" id = "cpassword" name="cpassword" required><br><br>
        <label for="email">New Email: </label>
        <input type="email" id = "email" name="email" required><br><br>
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


    if ($password != $cpassword) { //checking if password entry and confirm password entry match
        echo "Passwords do not match";
    }
    // Create connection
        $conn = makeConnection();
    $password = password_hash($password, PASSWORD_DEFAULT);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT username FROM internDatabase.users WHERE username = '$username'";
    $result = $conn->query($sql);


    if ($result->num_rows <= 0 ) {
        $sql1 = "SELECT email FROM internDatabase.users WHERE email = '$email'";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows <= 0) { //if never before used email, insert account info into DB
            $sql = "INSERT INTO internDatabase.users (username, password, email, wins) VALUES ('$username', '$password', '$email', 0)";
            $conn->query($sql);
            echo "<script> document.location.href='/index.php'</script>";
        }
        else {
            echo "Please choose another email";
        }
    } else { //checks to see if unique name
        echo "Please choose another username";
    }
    $conn->close();
    }
    if (isset($_GET['click'])) { //otherwise, insert new user into DB
        newUser();
    }
    //optional redirect below if user is trying to login
    ?>
    <p id> Already have an account? <a href = "index.php">Login.</a></p>


</div>
</body>
