<?php session_start();
include 'helper.php';?>
<style>
    <?php include 'style.css';?>
</style>
<body class="body">
    <div class="page-wrap">
    <h1>Are you sure you want to delete your account?</h1>

    <form method="get" class="yes" id="yes" action="deleteAccount.php">
        <input type="submit" name="yes" value = "Yes, delete my account" >
    </form>

    <form method="get" class="no" id="no" action="deleteAccount.php">
        <input type="submit" name="no" value = "No, don't delete my account" >
    </form>

        <?php
        // Permanently deletes a user's account
        function deleteAccount() {
            $confirm = "<script> window.confirm('Are you sure you want to delete your account?');</script>";
            if ($confirm) {
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

                // logout the user
                $user = $_SESSION['login_user'];
                $sql = "DELETE FROM internDatabase.onlineUsers WHERE username = '$user'";
                $conn->query($sql);

                //Destroying user's session
                session_destroy();
                session_unset();
                unset($_SESSION["loggedin"]);
                $_SESSION = array();

                // remove user from db
                $sql = "DELETE FROM internDatabase.users WHERE username = '$user'";
                $conn->query($sql);

                echo "<script> document.location.href='/index.php'</script>";
            }
        }

        if (isset($_GET['yes'])){
            deleteAccount();
        }
        if (isset($_GET['no'])){
            echo "<script> document.location.href='/lobby.php'</script>";
        }
        ?>

    </div>
</body>