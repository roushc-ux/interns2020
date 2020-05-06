<?php session_start() ?>
<style>
    <?php include 'style.css';?>
</style>
<body class="game">
<div class="page-wrap">
    <form method="post">
        <input id="addDeck" type="submit" name="addDeck" value = "Add Deck">
    </form>

    <div class="buttons-box">
        <div class="card-box">
<!--            <div class="card"> J </div>-->
<!--            <div class="card"> A </div>-->
            <?php
            include "deck.php";
            include "player.php";

            $constdeck = deckArray();

            if (!isset($_SESSION['test'])) {
                $_SESSION['test'] = 0;
            }

            // Get the player hands id
            if (!isset($_SESSION['sessionHandID'])) {
                $conn = makeConnection();
                $sql = "SELECT handID FROM hands ORDER BY handID DESC LIMIT 1";
                $result = $conn->query($sql);
                $_SESSION['sessionHandID'] = 0;
                $handID = 0;

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $_SESSION['sessionHandID'] = $row["handID"] + 1;
                    $handID = $_SESSION['sessionHandID'];
                }

                // insert into hand db
                $conn = makeConnection();
                $sql = "INSERT INTO hands (handID) VALUES ('$handID')";
                $conn->query($sql);
                $conn->close();
            }

            $p1 = new Player("name");

            $conn = makeConnection();
            $handID = $_SESSION['sessionHandID'];
            $sql = "SELECT cardID FROM cardsHand WHERE handID = '$handID'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $cardValMap = deckArray();
                    $card = $cardValMap[$row["cardID"]];
                    $cardValue = $card["Value"];

                    echo "<div class='card'>$cardValue</div>";
                }
            }

            $conn->close();

            ?>
        </div>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <div class="card">Player 1</div>
        </div>
    </div>
    <div class="buttons-box">
        <div class="card-box">
            <form method="post">
                <input type="submit" name="hit" value="Hit">
                <input type="submit" name="stay" value="Stay">
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['addDeck'])) {
    addDeck();
}
if (isset($_POST['hit'])) {
    hit();
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
    //  "<div class='card'>'$cardValue'</div>";
}

function addDeck() {
    $deck = new Deck();
    $deck->newFillDeck();
    $deck->pushToDb();

    if (!isset($_SESSION['sessionDeckID'])) {
        $_SESSION['sessionDeckID'] = $deck->getDeckID();
    }

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
</body>
