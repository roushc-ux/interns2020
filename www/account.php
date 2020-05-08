<?php include 'database.php';?>
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
        <label for="username">New Username: </label>
        <input type="text" id = "username" name="username" required><br><br>
        <label for="email">New Email: </label>
        <input type="email" id = "email" name="email" required><br><br>
        <label for="password">New Password: </label>
        <input type="password" id = "password" name="password" required><br><br>
        <label for="cpassword">Confirm Password: </label>
        <input type="password" id = "cpassword" name="cpassword" required><br><br>
        <input type="submit" name="click" value = "Create New Account">
    </form>

    <?php
        function newUser() {
        $username = $_GET["username"];
        $password = $_GET["password"];
        $email = $_GET["email"];
        $cpassword = $_GET["cpassword"];

        //Sanitize
        $username = filter_var($username, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);

        if ($password != $cpassword) { //checking if password entry and confirm password entry match
            echo "Passwords do not match";
        }
        // Create connection
        $conn = makeConnection();
        $password = password_hash($password, PASSWORD_DEFAULT);

        $result = selectResult('user', 'username', 'username', $username);

        if ($result->num_rows <= 0 ) {
            $result1 = select('user', 'email', 'email', $email);
            if ($result1->num_rows <= 0) { //if never before used email, insert account info into DB
                $sql = "INSERT INTO blackjack.user (username, password, email, wins) VALUES ('$username', '$password', '$email', 0)";
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

    <p id> Already have an account? <a href = "index.php" class = "link">Login.</a></p>
</div>
</body>
