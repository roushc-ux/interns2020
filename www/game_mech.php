<?php
    include "deck.php";
    include "player.php";
    include "helper.php";
    
    function resetGame() {
        // Clear database
        $conn = makeConnection();
        $sql = "DELETE FROM card_hand";
        $conn->query($sql);
        $sql = "DELETE FROM deck_hand"; //typo?
        $conn->query($sql);
        $sql = "DELETE FROM hand";
        $conn->query($sql);
        $sql = "DELETE FROM deck";
        $conn->query($sql);
        $sql = "DELETE FROM game";
        $conn->query($sql);
        $sql = "INSERT INTO game (gameID, deckID, discardID, playerTurn, numPlayers) VALUES (1, NULL, NULL, NULL, 0)";
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
        $sql = "INSERT INTO card_hand (handID, cardID) VALUES ('$handID', '$newCardID')";
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
        $sql = "UPDATE game SET deckID = '$deckID' WHERE gameID = 1";
        $conn->query($sql);

    //    if (!isset($_SESSION['sessionDeckID'])) {
    //        $_SESSION['sessionDeckID'] = $deck->getDeckID();
    //    }

        echo "new deck added";
    }

    function getTopCardDB() {
        $conn = makeConnection();
        $deckID = $_SESSION['sessionDeckID'];
        $sql = "SELECT * FROM card_deck WHERE deckID = '$deckID' ORDER BY cardOrder ASC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cardID = $row['cardID'];
            $sql = "DELETE FROM card_deck WHERE deckID = '$deckID' AND cardID = '$cardID'";
            $conn->query($sql);

            return $cardID;
        }
    }
?>