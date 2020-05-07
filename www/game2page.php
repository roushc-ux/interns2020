<?php session_start(); ?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta http-equiv="refresh" content="5">
</head>
<style>
    <?php include 'style.css';?>
</style>
<body class="game">
<div class="page-wrap">
<!--    <form method="post">-->
<!--        <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">-->
<!--    </form>-->

    <div class="buttons-box">
        <!-- Main player -->
        <div class="player">
            <div class="card-box">
                <?php
                include "helper.php";
                include "deck.php";
                include "player.php";

                getSessionHandID();
                getDeck();

                // Keep track of player info within session
                if (!isset($_SESSION['sessionPlayer'])) {
                    $player = new Player("name");
                    $_SESSION['sessionPlayer'] = serialize($player);
                }

                if (isset($_POST['reset'])) {
                    resetGame();
                }

                if (isset($_POST['hit'])) {
                    hit();
                }

                // Prints hand
                $player = unserialize($_SESSION['sessionPlayer']);
                printHand($player);

                //            if (!$player->isTurn() or $player->isBust()) {
                if ($player->isBust()) {
                    echo "<script type='text/javascript'>
                  $(document).ready(function()
                  {
                    $('#hitBtn').prop('disabled', true);
                    $('#stayBtn').prop('disabled', true);
                  });
                </script>";
                }
                ?>
            </div>
            <div class='card'>Player 1</div>
        </div>

        <!-- Other players -->
        <?php
        // Get all other players in room 1
        $conn = makeConnection();
        $currPlayerHandID = $_SESSION["sessionHandID"];
        $sql = "SELECT * FROM onlineUsers WHERE gameID = 1 AND handID <> '$currPlayerHandID'";
        $result = $conn->query($sql);

        // Divs for each player
        if ($result->num_rows > 0) {
            $playerIdx = 2;
            while($row = $result->fetch_assoc()) {
                echo "<div class='player'><div class='card-box'>";
                // Get the player's hand
                $handID = $row["handID"];
                $sql = "SELECT * from cardsHand WHERE handID = '$handID'";
                $cards_query = $conn->query($sql);

                if ($cards_query->num_rows > 0) {
                    while($card_row = $cards_query->fetch_assoc()) {
                        $cardValMap = deckArray();
                        $card = $cardValMap[$card_row['cardID']];
                        $cardValue = $card['Value'];
                        echo "<div class='card'>$cardValue</div>";
                    }
                }
                echo "</div><div class='card'>Player $playerIdx</div></div>";
                $playerIdx++;
            }
        }
//        for ($i = 0; $i < $numPlayers - 1; $i++) {
//            $playerIdx = $i + 2;
//
//            // Get the player's hand
//            $sql = "SELECT cardsHand.* FROM onlineUsers
//                    INNER JOIN cardsHand on onlineUsers.handID = cardsHand.handID
//                    WHERE onlineUsers.gameID = 1";
//            $result = $conn->query($sql);
//
//            echo "
//            <div class='player'>
//                <div class='card-box'></div>
//                <div class='card'>Player $playerIdx</div>
//            </div>
//            ";
//        }

        $conn->close();
        ?>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <form method="post">
                <input id="hitBtn" type="submit" name="hit" value="Hit">
                <input id="stayBtn" type="submit" name="stay" value="Stay">
                <input type="submit" name="reset" value="Reset">
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['addDeck'])) {
    addDeck();
}

function resetGame() {
    // Clear database
    $conn = makeConnection();
    $sql = "DELETE FROM cardsHand";
    $conn->query($sql);
    $sql = "DELETE FROM decksHand";
    $conn->query($sql);
    $sql = "DELETE FROM hands";
    $conn->query($sql);
    $sql = "DELETE FROM decks";
    $conn->query($sql);
    $sql = "DELETE FROM games";
    $conn->query($sql);
    $sql = "INSERT INTO games (gameID, deckID, discardID, playerTurn, numPlayers) VALUES (1, NULL, NULL, NULL, 0)";
    $conn->query($sql);
    $conn->close();

    // Clear player hand in current session
    $player = unserialize($_SESSION['sessionPlayer']);
    $player->emptyHand();
    $_SESSION['sessionPlayer'] = serialize($player);
    echo "reset";
}

function getSessionHandID() {
    // Get the player hands id
    if (!isset($_SESSION['sessionHandID'])) {
        $conn = makeConnection();
        $sql = "SELECT * FROM hands ORDER BY handID DESC LIMIT 1";
        $result = $conn->query($sql);
        $_SESSION['sessionHandID'] = 0;
        $handID = 0;

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['sessionHandID'] = $row["handID"] + 1;
            $handID = $_SESSION['sessionHandID'];
        }

        // insert into hand db
        $sql = "INSERT INTO hands (handID) VALUES ('$handID')";
        $conn->query($sql);

        // Insert into onlineUsers table
        // TODO: name below is just for testing currently. Replace name with appropriate username
        $names = ['', 'name'];
        $name = $names[$handID];
        $sql = "INSERT INTO onlineUsers (username, gameID, money, handID) VALUES ('$name', 1, 100, '$handID')";
        $conn->query($sql);

        // Update number of player in game
        $sql = "SELECT * FROM games WHERE gameID = 1";
        $result = $conn->query($sql);
        $result = $result->fetch_assoc();
        $newNumPlayers = $result["numPlayers"] + 1;
        $sql = "UPDATE games SET numPlayers = '$newNumPlayers' WHERE gameID = 1";
        $conn->query($sql);

        $conn->close();
    }
}

function hit() {
    // Draw card
    $newCardID = getTopCardDB();

    // Add card to hand db
    $conn = makeConnection();
    $handID = $_SESSION['sessionHandID'];
    $sql = "INSERT INTO cardsHand (handID, cardID) VALUES ('$handID', '$newCardID')";
    $conn->query($sql);
    $conn->close();

    // Insert card into hand of current player session
    // Update if player bust
    $cardValMap = deckArray();
    $card = $cardValMap[$newCardID];
    $player = unserialize($_SESSION["sessionPlayer"]);
    $player->addCard($card);
    $player->checkBust();
    $_SESSION["sessionPlayer"] = serialize($player);

//    $cardValue = $card["Value"];
//    echo "<div class='card'>$cardValue</div>";
    //  "<div class='card'>'$cardValue'</div>";
}

function printHand($player) {
    $hand = $player->getHand();
    for ($i = 0; $i < count($hand); $i++) {
        $cardValue = $hand[$i]['Value'];
        echo "<div class='card'>$cardValue</div>";
    }
    $score = $player->calcHand();
    echo "<div>Score: $score</div>";
}

function addDeck() {
    $deck = new Deck();
    $deck->newFillDeck();
    $deck->pushToDb();
    $deckID = $deck->getDeckID();
    $_SESSION['sessionDeckID'] = $deckID;

    // Add deckID to games
    $conn = makeConnection();
    $sql = "UPDATE games SET deckID = '$deckID' WHERE gameID = 1";
    $conn->query($sql);

//    if (!isset($_SESSION['sessionDeckID'])) {
//        $_SESSION['sessionDeckID'] = $deck->getDeckID();
//    }

    echo "new deck added";
}

function getDeck() {
    if (!isset($_SESSION['sessionDeckID'])) {
        $conn = makeConnection();
        $sql = "SELECT * FROM games WHERE gameID = 1";
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

function getTopCardDB() {
    $conn = makeConnection();
    $deckID = $_SESSION['sessionDeckID'];
    $sql = "SELECT * FROM cardsDeck WHERE deckID = '$deckID' ORDER BY cardOrder ASC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cardID = $row['cardID'];
        $sql = "DELETE FROM cardsDeck WHERE deckID = '$deckID' AND cardID = '$cardID'";
        $conn->query($sql);

        return $cardID;
    }
}
?>
</body>
