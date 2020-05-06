<?php
    include 'helper.php';

    class Deck {
        private $deck;
        private $deckID;
        
        
        public function __construct() {
            $this->deck = array();

            // Peeks inside the db to get the deckID;
            $conn = makeConnection();
            $sql = "SELECT deckID FROM decks ORDER BY deckID DESC LIMIT 1";
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
            $sql = "INSERT INTO decks (deckID) VALUES ('$this->deckID')";
            $conn->query($sql);
        }

        public function getDeck() {
            return $this->deck;
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


        public function fillDeck() {
            $values = array("A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K",);
            // 0: Spades    1: Hearts   2:  Clubs   3:  Diamonds
            $suits = array(0, 1, 2, 3,);
            define('NUMVALS', 13);
            define('VALSPERDECK', 4);
            for($i = 0; $i < NUMVALS; ++$i) {
                for($k = 0; $k < VALSPERDECK; ++$k) {
                    $weight = intval($values[$i]);
                    if ($values[$i] == "J" || $values[$i] == "Q" || $values[$i] == "K") {
                        $weight = 10;
                    } else if ($values[$i] == "A") {
                        $weight = 1;
                    }
                    $card = array("Value" => $values[$i], "Weight" => $weight, "Suit" => $suits[$k],);
                    $this->deck[] = $card;
                }
            }
        }
    
        function shuffleDeck() {
            $deckInLength = count($this->deck);
            $deck1 = array();
            $deck2 = array();
            for($i = 0; $i < $deckInLength; ++$i) {
                if ($i % 2 == 0) {
                    $deck1[] = array_shift($this->deck);
                } else {
                    $deck2[] = array_shift($this->deck);
                }
            }
            $deck1Length = count($deck1);
            for ($i = 0; $i < $deck1Length; ++$i) {
                $this->deck[] = array_shift($deck1);
            }
            $deck2Length = count($deck2);
            for ($i = 0; $i < $deck2Length; ++$i) {
                $this->deck[] = array_shift($deck2);
            }
        }
    
        function printDeck() {
            print_r($this->deck);
        }

        function pushToDb() {
            $conn = makeConnection();

            for ($i = 0; $i < count($this->deck); $i++) {
                $cardID = getCardID($this->deck[$i]);

                // Remove old deck info from db
                $sql = "DELETE FROM cardsDeck WHERE deckID = '$this->deckID'";

                // Add cards to db
                $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES ('$this->deckID', '$cardID', '$i')";
                $conn->query($sql);
            }
        }
    }
?>