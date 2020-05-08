<?php
    session_start();
    include_once 'game_mech.php';

    function initSession() {
        kickIfFull();
//        getSessionPlayer();
        updateGameInfo();
        getSessionHandID();
        getSessionDeckID();
        getDealerID();
    }

    function kickIfFull() {
        $conn = makeConnection();
        $username = $_SESSION['login_user'];
        $sql = "SELECT gameID FROM blackjack.online_user WHERE username = '$username'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $gameID = $row["gameID"];

        $sql = "SELECT numPlayers FROM game WHERE gameID = 1 LIMIT 1";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);

        // Full game = 2 people and you have not registered --> kick
        if ($row['numPlayers'] == 2 and is_null($gameID)) {
            echo "<script>alert('Game is currently full. Come back later :(')</script>";
            // header('Location:lobby.php');
            echo "<script type='text/javascript'>location.href = '/lobby.php';</script>";
            exit;
        };
    }

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

            // update handID and money for player
            $username = $_SESSION['login_user'];
//            $sql = "UPDATE online_user SET gameID = 1 WHERE username = '$username'";
//            $conn->query($sql);
            $sql = "UPDATE online_user SET handID = '$handID' WHERE username = '$username'";
            $conn->query($sql);
            $sql = "UPDATE online_user SET money = 100 WHERE username = '$username'";
            $conn->query($sql);

            // Update number of player in game
//            $sql = "SELECT * FROM game WHERE gameID = 1";
//            $result = $conn->query($sql);
//            $result = $result->fetch_assoc();
//            $newNumPlayers = $result["numPlayers"] + 1;
//            $sql = "UPDATE game SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
//            $conn->query($sql);
//
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

    function getPlayerID() {
        $conn = makeConnection();
        $username = $_SESSION['login_user'];
        $sql = "SELECT playerID FROM online_user WHERE username = '$username'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
//        echo "playerId: " . $row['playerID'];
        $conn->close();
        return $row['playerID'];
    }

    // Update player's gameID, playerID, and game numPlayers.
    function updateGameInfo() {
        $conn = makeConnection();
        $username = $_SESSION['login_user'];
        $sql = "SELECT gameID FROM blackjack.online_user WHERE username = '$username'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        if (is_null($row["gameID"])) {
            // If player just joined (gameID == NULL) --> reset player session var
            getSessionPlayer();

            $sql = "UPDATE online_user SET gameID = 1 WHERE username = '$username'";
            $conn->query($sql);
            $sql = "SELECT numPlayers FROM game WHERE gameID = 1 LIMIT 1";
            $result = $conn->query($sql);
            $row = mysqli_fetch_array($result);
            $playerID = $row['numPlayers']; // added this?
            $sql = "UPDATE online_user SET playerID = '$playerID' WHERE username = '$username'";
            $conn->query($sql);
            $newNumPlayers = $playerID + 1;
            $sql = "UPDATE game SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
            $conn->query($sql);
        }
        $conn->close();
    }

    function getDealerID() {
        $conn = makeConnection();
        $sql = "SELECT dealerHandID FROM game WHERE gameID = 1";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $dealerID = 0;

        // Init dealer if there's no dealer yet
        if (is_null($row["dealerHandID"])) {
            $sql = "SELECT * FROM hand ORDER BY handID DESC LIMIT 1";
            $result = $conn->query($sql);
//            $_SESSION['sessionDealerID'] = 0;

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $dealerID = $row["handID"] + 1;
//                $_SESSION['sessionDealerID']= $dealerID;
            }

            // insert into hand db
            $sql = "INSERT INTO hand (handID) VALUES ('$dealerID')";
            $conn->query($sql);

            // Update dealerID in game db
            $sql = "UPDATE game SET dealerHandID = $dealerID WHERE gameID = 1";
            $conn->query($sql);
        }
        else {
            $dealerID = $row["dealerHandID"];
        }

        $conn->close();
        return $dealerID;
    }

        function getSessionPlayer() {
            // Keep track of player info within session
            // If player just joined (gameID == NULL) --> reset player session var
            unset($_SESSION['sessionPlayer']);
            $username = $_SESSION['login_user'];
            $player = new Player ($username);
            $_SESSION['sessionPlayer'] = serialize($player);
        }

//    function getSessionPlayer() {
//        // Keep track of player info within session
//        // If player just joined (gameID == NULL) --> reset player session var
//        if (!isset($_SESSION['sessionPlayer'])) {
//            // $player = new Player("name");
//            $username = $_SESSION['login_user'];
//            $player = new Player ($username);
//            $_SESSION['sessionPlayer'] = serialize($player);
//        }
//    }

    function unsetSessions() {
        unset($_SESSION['sessionHandID']);
        unset($_SESSION['sessionDeckID']);
        unset($_SESSION['sessionPlayer']);
    }
?>