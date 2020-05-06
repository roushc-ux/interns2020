 <?php
    function addDeck() {
        echo "ADD DECK CALLED";
        $deck = new Deck;
        $deck->newFillDeck();
        $deck->shuffleDeck();
        $testCards = $deck->getDeck();

        $conn = makeConnection();

        $sql = "INSERT INTO decks (deckID) VALUES (1)";
        $result = $conn->query($sql);

        // Testing different implementation
        for ($i=0; $i<52; $i++) {
            $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES (1, '$testCards[$i]', '$i')";
            $conn->query($sql);
        }

        echo "Success";
        $conn->close();
    }

    function delDeck() {
        $conn = makeConnection();
        $sql = "DELETE FROM cardsDeck";
        $conn->query($sql);
        $conn->close();
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