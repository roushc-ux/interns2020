<?php
    define("SERVERNAME", "localhost", true);
    define("USERNAMESERVER", "interns2020", true);
    define("PASSWORD", "interns2020", true);

    function makeConnection() {
        $servername = SERVERNAME;
        $usernameServer = USERNAMESERVER;
        $passwordServer = PASSWORD;
        $dbname = "blackjack";

        // Create connection
        $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    function insert($tableName, $tableColumn, $value) {
        $conn = makeConnection();
        $sql = "INSERT INTO $tableName ($tableColumn) VALUES ('$value')";
        $conn->query($sql);
    }

    function select($tableName, $tableColumn, $condition1, $condition2) {
        $conn = makeConnection();
        $sql = "SELECT $tableColumn FROM $tableName WHERE $condition1 = '$condition2'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        return $row;
    }
    
    function update($tableName, $tableColumn, $value, $condition1, $condition2) {
        $conn = makeConnection();
        $sql = "UPDATE $tableName SET $tableColumn = '$value' WHERE '$condition1' = '$condition2'";
        $conn->query($sql);
    }



?>