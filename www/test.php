<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="test.js"></script>
    <script src="deck.js"></script>
    <script src="player.js"></script>
</head>
<body>
    <button>Send GET Request</button>
    
    <div id="newDeck">New deck</div>

    <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">

    <?php
    include 'player.php';
    include 'deck.php';

    function addDeck() {
        $deck = new Deck;
        $deck->fillDeck();
        $deck->printDeck();
    }

    if (isset($_POST['action'])) {
        addDeck();
    }

    ?>
</body>