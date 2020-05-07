<?php session_start();
include 'database.php';?>
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
            // make connection to db
            $conn = makeConnection();

            // logout the user
            $user = $_SESSION['login_user'];
            $sql = "DELETE FROM blackjack.online_user WHERE username = '$user'";
            $conn->query($sql);

            //Destroying user's session
            session_destroy();
            session_unset();
            unset($_SESSION["loggedin"]);
            $_SESSION = array();

            // remove user from db
            $sql = "DELETE FROM blackjack.user WHERE username = '$user'";
            $conn->query($sql);

            echo "<script> document.location.href='/index.php'</script>";
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