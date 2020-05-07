<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="test.js"></script>
    <script src="deck.js"></script>
    <script src="player.js"></script>
    <title>Test</title>
</head>
<body>
    <div id="newDeck">New deck</div>

    <form method="post">
        <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">
    </form>

    <div class="container" id="output"></div>

    <?php
//    include 'helper.php';
    include 'player.php';
    include 'deck.php';

    function addDeck() {
        // testing different implementation
        $testDeck = new Deck;
        $testDeck->newFillDeck();
        $testCards = $testDeck->getDeck();
        $testDeckID = $testDeck->getDeckID();

        $conn = makeConnection();

        $sql = "INSERT INTO deck (deckID) VALUES ('$testDeckID')";
        $result = $conn->query($sql);

        // Testing different implementation
        for ($i=0; $i<52; $i++) {
            $sql = "INSERT INTO card_deck (deckID, cardID, cardOrder) VALUES ('$testDeckID', '$testCards[$i]', '$i')";
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