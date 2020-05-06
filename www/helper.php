<!--Helper functions that might (not) be helpful-->

<?php
    define("SERVERNAME", "localhost", true);
    define("USERNAMESERVER", "root", true);
    define("PASSWORD", "interns2020", true);

//    define("SERVERNAME", "dmilazterns01", true);
//    define("USERNAMESERVER", "interns2020", true);
//    define("PASSWORD", "interns2020", true);

    // Returns the connection made where we can query on
    // Change the consts above accordingly
    function makeConnection() {
        $servername = SERVERNAME;
        $usernameServer = USERNAMESERVER;
        $passwordServer = PASSWORD;
        $dbname = "intern2020";

        // Create connection
        $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
?>