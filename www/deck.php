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

            for ($i = 0; $i < count($this->deck); $i++) {
                $cardID = getCardID($this->deck[$i]);

                // Remove old deck info from db
                $sql = "DELETE FROM cardsDeck WHERE deckID = '$this->deckID'";

                // Add cards to db
                $sql = "INSERT INTO cardsDeck (deckID, cardID, cardOrder) VALUES ('$this->deckID', '$cardID', '$i')";
                $conn->query($sql);
            }
        }

        function getDeckFromDB() {
            //Create a map between cardOrder and card
            $cardValMap = new Deck;
            $cardValMap->fillDeck();

            $conn = makeConnection();
            $query = 'SELECT * FROM cardsDeck WHERE deckID = 1'; //Change deckID
            $result = $conn->query($query);

            //Map cardOrder and add card to deck
            while($row = $result->fetch_array()) {
                $arr = $cardValMap->getDeck();
                $card = $arr[$row[2]];
                $deck->addCard($card);
            }
            $deck->printDeck();

            $conn->close();
        }

        function getTopCardDB() {
            $conn = makeConnection();
            $sql = "SELECT * FROM cardsDeck WHERE deckId = 1 ORDER BY cardOrder ASC LIMIT 1";
            $card = $conn->query($sql);
            $query = "DELETE FROM cardsDeck WHERE deckID = '$card[0]' AND cardID = '$card[1]'";
            $cardValMap = new Deck;
            $cardValMap->fillDeck();
            $temp = $cardValMap->getDeck();
            $card = $temp[$card[2]];
            return $card;
        }
    }
?>