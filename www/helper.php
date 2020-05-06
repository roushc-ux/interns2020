<!--Helper functions that might (not) be helpful-->

<?php
    define("PASSWORD", "interns2020", true);

    // Returns the connection made where we can query on
    // If run locally, change the PASSWORD const above
    function makeConnection() {
        $servername = "localhost";
        $usernameServer = "root";
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