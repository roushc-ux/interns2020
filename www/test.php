<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="test.js"></script>
    <script src="deck.js"></script>
    <script src="player.js"></script>
</head>
<body>
    <div id="newDeck">New deck</div>

    <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">

    <?php
    include 'player.php';
    include 'deck.php';

    function addDeck() {
        $deck = new Deck;
        $deck->fillDeck();

        $cards = $deck->getDeck();

        $servername = "localhost";
        $usernameServer = "root";
        $passwordServer = "yourpw";
        $dbname = "intern2020";

        // Create connection
        $conn = new mysqli($servername, $usernameServer, $passwordServer, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO decks (deckID) VALUES (1)";
        $result = $conn->query($sql);

        for ($i = 0; $i < count($cards); $i++) {
            $cardID = ($cards[i]["Weight"] - 1) * 4 + $cards[i]["Suit"];
            $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES (1, '$cardID', '$i')";
        }
    }

    if (isset($_POST['action'])) {
        addDeck();
    }

    ?>
</body>