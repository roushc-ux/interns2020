<?php
    include "deck.php";
    include "player.php";
    include "helper.php";
    
    function resetGame() {
        // Clear database
        $conn = makeConnection();
        $sql = "DELETE FROM cardsHand";
        $conn->query($sql);
        $sql = "DELETE FROM decksHand"; //typo?
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