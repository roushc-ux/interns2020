<?php session_start();
    include "deck.php";
    include "player.php";
    include "helper.php";


//    // intend to use this when the round (NOT game) has ended
//    function newRound() {
//        // Clear player hand in current session
//        // We're not unsetting the entire sessionPlayer here because
//        // then money would get reset every time we reset the game.
//        // Instead we just empty their hands now.
//        $player = unserialize($_SESSION['sessionPlayer']);
//        $player->emptyHand();
//        $_SESSION['sessionPlayer'] = serialize($player);
//
//        // Delete current hand from db
//        // Might wanna add things here to handle discard?
//        $conn = makeConnection();
//        $handID = $_SESSION['sessionHandID'];
//        $sql = "DELETE FROM card_hand WHERE handID = $handID";
//        $conn->query($sql);
//
//        // Delete current dealer hand from db
//        $dealerID = getDealerID();
//        $sql = "DELETE FROM card_hand WHERE handID = $dealerID";
//        $conn->query($sql);
//
//        // Reset turn
//        $sql = "UPDATE game SET playerTurn = 0 WHERE gameID = 1";
//        $conn->query($sql);
//
//        echo "new round";
//    }

    // unset all sessions and update db
    // intend to use this when the game (NOT round) has ended
    function newGame() {
        $handID = $_SESSION['sessionHandID'];
        unsetSessions();

        $conn = makeConnection();

        // Delete all cards in hand in db
        $sql = "DELETE FROM card_hand WHERE handID = $handID";
        $conn->query($sql);

        // Delete dealer hand
        $dealerID = getDealerID();
        $sql = "DELETE FROM card_hand WHERE handID = $dealerID";
        $conn->query($sql);

        // Delete deck currently used for the game
        $sql = "SELECT deckID FROM game WHERE gameID = 1";
        $conn->query($sql);
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $deckID = $row['deckID'];
        $sql = "DELETE FROM card_deck WHERE deckID = $deckID";
        $conn->query($sql);
        $sql = "DELETE FROM deck WHERE deckID = $deckID";
        $conn->query($sql);

        // Reset game db
        $sql = "DELETE FROM game WHERE gameID = 1";
        $conn->query($sql);
        $sql = "INSERT INTO game (gameID, deckID, discardID, playerTurn, numPlayers, dealerHandID) VALUES (1, NULL, NULL, 0, 0, NULL)";
        $conn->query($sql);
        $conn->close();
    }

    function resetDb() {
        // Clear database
        $conn = makeConnection();
        $sql = "DELETE FROM card_hand";
        $conn->query($sql);
        $sql = "DELETE FROM card_deck";
        $conn->query($sql);
        $sql = "DELETE FROM deck";
        $conn->query($sql);
        $sql = "DELETE FROM game";
        $conn->query($sql);
        $sql = "DELETE FROM online_user";
        $conn->query($sql);
        $sql = "DELETE FROM hand";
        $conn->query($sql);
        $sql = "INSERT INTO game (gameID, deckID, discardID, playerTurn, numPlayers, dealerHandID) VALUES (1, NULL, NULL, 0, 0, NULL)";
        $conn->query($sql);
        $conn->close();
    }

    function newRound() {
        //Add player hands to discard
        $conn = makeConnection();
        $row = select("game", "numPlayers", "gameID", $gameID);
        $numPlayers = $row['numPlayers'];

        //For each player, get cards, add to discard, and delete from hand
        for($i = 0; $i < $numPlayers; ++$i) {
            $handID = select("online_user", "handID", "playerID", $i);
            $handID = $handID['handID'];
            echo $handID;
            $cards = select("card_hand", "cardID", "handID", $handID);
            print_r($cards);
            foreach($cards as $cardID) {
                $sql = "INSERT INTO card_discard (discardID, cardID) VALUES (1, $cardID)";
                $conn->query($sql);
            }
            $sql = "DELETE FROM card_hand WHERE handID = $handID";
            $conn->query($sql);
            $sql = "DELETE FROM hand WHERE handID = $handID";
            $conn->query($sql);
        }
        $conn->close();
    }

    function endRound() {
        $dealer = getDealer();
        $dealerScore = $dealer->calcHand();
        $player = unserialize($_SESSION['sessionPlayer']);
        $playerScore = $player->calcHand();

        if(!$player->checkBust() && $dealer->checkBust()) {
            addMoney($player, 20);
        }
        else if ($dealerScore == $playerScore) {
            addMoney($player, 10);
        }
        else if (!$player->checkBust() && $dealerScore < $playerScore) {
            addMoney($player, 20);
        }
    }

    function dealerTurn() {
        $dealer = getDealer();
        $dealerScore = $dealer->calcHand();
        while ($dealerScore < 16) {
            $newCardID = getTopCardDB();
            $handID = $_SESSION['sessionHandID'];
            $sql = "INSERT INTO card_hand (handID, cardID) VALUES ('$dealerHandID', '$newCardID')";
            $conn->query($sql);
        }
    }

    function startGame() {
        $conn = makeConnection();
        $sql = "SELECT numPlayers FROM game WHERE gameID = 1 LIMIT 1";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);

        // Start game when reach X players
        $player = unserialize($_SESSION['sessionPlayer']);
        if (($row['numPlayers'] >= 2) and ($player->numCards() == 0)) {
            hit();
            hit();

            // Dealer draws 2 if haven't
            $dealerHandID = getDealerID();
            $sql = "SELECT * FROM card_hand WHERE handID = $dealerHandID";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                for ($i = 0; $i < 2; $i++) {
                    $newCardID = getTopCardDB();
                    // Add card to hand db
                    $handID = $_SESSION['sessionHandID'];
                    $sql = "INSERT INTO card_hand (handID, cardID) VALUES ('$dealerHandID', '$newCardID')";
                    $conn->query($sql);
                }
            }
        }

        $conn->close();
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

        // switch turn to next player if bust
        if ($player->checkBust()) {
            echo "<script>alert('Bust! Youre out!')</script>";
            incrementTurn();
        }
        // switch turn to next player and alert if 21 or 5 card charlie
        else if ($player->calcHand() == 21) {
            echo "<script>alert('Blackjack!')</script>";
            incrementTurn();
        }
        else if ($player->numCards() == 5) {
            echo "<script>alert('5 Card Charlie!')</script>";
            incrementTurn();
        }
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

        // Add deckID to game
        $conn = makeConnection();
        $sql = "UPDATE game SET deckID = '$deckID' WHERE gameID = 1";
        $conn->query($sql);
        $conn->close();

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
            $conn->close();

            return $cardID;
        }
    }

    function incrementTurn() {
        $conn = makeConnection();
        $sql = "SELECT playerTurn FROM game WHERE gameID = 1 LIMIT 1";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $nextTurn = $row["playerTurn"] + 1;

        //check if new round needs to start. Call dealer function, call new round, set playerTurn=0
        $sql = "SELECT numPlayers FROM game WHERE gameID = $gameID LIMIT 1";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $numPlayers = $row["numPlayers"];
        if ($nextTurn >= $numPlayers) { //playerID is 0 indexed so when numPlayers=3, last player should be 2.
            dealerTurn();
            endRound();
            newRound();
            $nextTurn = 0;
        }

        $sql = "UPDATE game SET playerTurn = $nextTurn WHERE gameID = 1";
        $conn->query($sql);
        $conn->close();
    }

//    function checkNewRound() {
//        $conn = makeConnection();
//        $sql = "SELECT numPlayers FROM game WHERE gameID = 1 LIMIT 1";
//        $result = $conn->query($sql);
//        $row = mysqli_fetch_array($result);
//        $numPlayers = $row['numPlayer'];
//
//        $sql = "SELECT playerTurn FROM game WHERE gameID = 1 LIMIT 1";
//        $result = $conn->query($sql);
//        $row = mysqli_fetch_array($result);
//        // new round if playerTurn = numPlayers + 1
//        // new round could be if playerTurn = numPlayers + 2 (in case we wanna use playerTurn numPlayers + 1 as dealer's turn)
//        if ($row['playerTurn'] == $numPlayers + 1) {
//            newRound();
//            header("Refresh:0");
//        }
//    }
?>