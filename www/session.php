<?php
    function getSessionHandID() {
        // Get the player hands id
        if (!isset($_SESSION['sessionHandID'])) {
            $conn = makeConnection();
            $sql = "SELECT * FROM hand ORDER BY handID DESC LIMIT 1";
            $result = $conn->query($sql);
            $_SESSION['sessionHandID'] = 0;
            $handID = 0;

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['sessionHandID'] = $row["handID"] + 1;
                $handID = $_SESSION['sessionHandID'];
            }

            // insert into hand db
            $sql = "INSERT INTO hand (handID) VALUES ('$handID')";
            $conn->query($sql);

            // Insert into onlineUsers table
            // TODO: name below is just for testing currently. Replace name with appropriate username
            // $names = ['', 'name'];
            // $name = $names[$handID];
            $username = $_SESSION['login_user'];
            $sql = "UPDATE online_user SET gameID = 1 WHERE username = '$username'";
            $conn->query($sql);
            $sql = "UPDATE online_user SET handID = '$handID' WHERE username = '$username'";
            $conn->query($sql);
            $sql = "UPDATE online_user SET money = 100 WHERE username = '$username'";
            $conn->query($sql);

            // Update number of player in game
            $sql = "SELECT * FROM game WHERE gameID = 1";
            $result = $conn->query($sql);
            $result = $result->fetch_assoc();
            $newNumPlayers = $result["numPlayers"] + 1;
            $sql = "UPDATE game SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
            $conn->query($sql);

            $conn->close();
        }
    }

    // Updates sessionDeckID
    function getSessionDeckID() {
        if (!isset($_SESSION['sessionDeckID'])) {
            $conn = makeConnection();
            $sql = "SELECT * FROM game WHERE gameID = 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            // Create new deck if current game doesn't have a deck
            if (is_null($row["deckID"])) {
                addDeck();
            }
            // Update session deckID if deck already exists
            else {
                $_SESSION['sessionDeckID'] = $row["deckID"];
            }
        }
    }
?>