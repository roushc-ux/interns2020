<!--Helper functions that might (not) be helpful-->

<?php
    define("SERVERNAME", "localhost", true);
    define("USERNAMESERVER", "interns2020", true);
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
        $dbname = "internDatabase";

        // Create connection
        $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    // Calculates the cardID given the dictionary of card info
    function getCardID($card) {
        $cardPos = $card["Weight"];
        if ($card["Value"] == "J") {
            $cardPos = 11;
        }
        else if ($card["Value"] == "Q") {
            $cardPos = 12;
        }
        else if ($card["Value"] == "K") {
            $cardPos = 13;
        }
       return ($cardPos - 1) * 4 + $card["Suit"];
    }

?>