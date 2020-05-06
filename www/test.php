<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="test.js"></script>
    <script src="deck.js"></script>
    <script src="player.js"></script>
</head>
<body>
    <div id="newDeck">New deck</div>

    <form method="post">
        <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">
    </form>

    <?php
    include 'helper.php';
    include 'player.php';
    include 'deck.php';

    function addDeck() {
        $deck = new Deck;
        $deck->fillDeck();

        $cards = $deck->getDeck();

        // testing different implementation
        $testDeck = new Deck;
        $testDeck->newFillDeck();
        $testCards = $testDeck->getDeck();

        $conn = makeConnection();

        $sql = "INSERT INTO decks (deckID) VALUES (1)";
        $result = $conn->query($sql);

        // Testing different implementation
        for ($i=0; $i<52; $i++) {
            $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES (1, '$testCards[$i]', '$i')";
            $conn->query($sql);
        }

        /*
        for ($i = 0; $i < count($cards); $i++) {
            $cardPos = $cards[$i]["Weight"];
            if ($cards[$i]["Value"] == "J") {
                $cardPos = 11;
            }
            else if ($cards[$i]["Value"] == "Q") {
                $cardPos = 12;
            }
            else if ($cards[$i]["Value"] == "K") {
                $cardPos = 13;
            }
            $cardID = ($cardPos - 1) * 4 + $cards[$i]["Suit"];
            // $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES (1, '$cardWeight', '$cardSuit')";
            $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES (1, '$cardID', '$i')";
            $conn->query($sql);
        }
        */

        echo "Success";
        $conn->close();
    }

    if (isset($_POST['addDeck'])) {
        addDeck();
    }

    ?>
</body>