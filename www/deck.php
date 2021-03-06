<?php
include "database.php";
// Calculates the cardID given the dictionary of card info
function getCardID($card) {
    $cardPos = $card["Weight"];
    if ($card["Value"] == "J") {
        $cardPos = 11;
    }
    else if ($card["Value"] == "Q") {
        $cardPos = 12;
    }
    else if ($card["Value"] == "K") {
        $cardPos = 13;
    }
    return ($cardPos - 1) * 4 + $card["Suit"];
}

function deckArray() {
    $values = array("A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K",);
    // 0: Spades    1: Hearts   2:  Clubs   3:  Diamonds
    $suits = array(0, 1, 2, 3,);
    $deck = [];
    define('NUMVALS', 13);
    define('VALSPERDECK', 4);
    for($i = 0; $i < NUMVALS; ++$i) {
        for($k = 0; $k < VALSPERDECK; ++$k) {
            $weight = intval($values[$i]);
            if ($values[$i] == "K" || $values[$i] == "Q" || $values[$i] == "J") {
                $weight = 10;
            } else if ($values[$i] == "A") {
                $weight = 1;
            }
            $card = array("Value" => $values[$i], "Weight" => $weight, "Suit" => $suits[$k],);
            $deck[] = $card;
        }
    }
    return $deck;
}
    class Deck {
        private $deck;
        private $deckID;




        public function __construct() {
            $this->deck = array();

            // Peeks inside the db to get the deckID;
            $conn = makeConnection();
            $sql = "SELECT deckID FROM deck ORDER BY deckID DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $this->deckID = $row["deckID"] + 1;
                }
            }
            // Empty decks table
            else {
                $this->deckID = 1;
            }

            // Push new deck into db
            $sql = "INSERT INTO deck (deckID) VALUES ('$this->deckID')";
            $conn->query($sql);
            $conn->close();
        }

        public function getDeck() {
            return $this->deck;
        }

        public function getDeckID() {
            return $this->deckID;
        }

        /**
         * @param int|mixed $deckID
         */
        public function setDeckID($deckID) {
            $this->deckID = $deckID;
        }

        public function getCard() {
            return array_shift($this->deck);
        }

        public function addCard($card) {
            $this->deck[] = $card;
        }

        // testing different implementation
        public function newFillDeck() {
            for ($i=0; $i<52; $i++) {
                $this->deck[] = $i;
            }
            $this->shuffleDeck();
        }

        public function fillDeckDiscards() {
            $cardValMap = new Deck;
            $cardValMap->fillDeck();

            $conn = makeConnection();
            $query = 'SELECT * FROM card_discard WHERE discardID = 1'; //Change deckID
            $result = $conn->query($query);

            //Map cardOrder and add card to deck
            while($row = $result->fetch_array()) {
                $arr = $cardValMap->getDeck();
                $card = $arr[$row[2]];
                $this->addCard($card);
            }
            $this->shuffleDeck();
        }

        public function fillDeck() {
            $this->deck = deckArray();
        }

        function shuffleDeck() {
            $deckLength = count($this->deck);
            for($i = $deckLength - 1; $i >= 0; $i--) {
                $j = rand(0, $i + 1);
                $temp = $this->deck[$i];
                $this->deck[$i] = $this->deck[$j];
                $this->deck[$j] = $temp;
            }
        }

        function printDeck() {
            echo '<pre>';
            print_r($this->deck);
            echo '</pre>';
        }

        function pushToDb() {
            $conn = makeConnection();

            // Remove old deck info from db
            $sql = "DELETE FROM card_deck WHERE deckID = '$this->deckID'";
            $conn->query($sql);

            // Add cards to db
            for ($i = 0; $i < count($this->deck); $i++) {
                $cardID = $this->deck[$i];
                $sql = "INSERT INTO card_deck (deckID, cardID, cardOrder) VALUES ('$this->deckID', '$cardID', '$i')";
                $conn->query($sql);

            }
            $conn->close();
        }

        function getDeckFromDB() {
            //Create a map between cardOrder and card
            $cardValMap = new Deck;
            $cardValMap->fillDeck();

            $conn = makeConnection();
            $query = 'SELECT * FROM card_deck WHERE deckID = 1'; //Change deckID
            $result = $conn->query($query);

            //Map cardOrder and add card to deck
            while($row = $result->fetch_array()) {
                $arr = $cardValMap->getDeck();
                $card = $arr[$row[2]];
                $this->deck->addCard($card);
            }
            $this->deck->printDeck();

            $conn->close();
        }

    }
?>