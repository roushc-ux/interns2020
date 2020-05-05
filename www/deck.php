<?php 
    class Deck {
        private $deck;
        
        
        public function __construct() {
            $this->deck = array();
        }
        
        public function getCard() {
            return array_shift($this->deck);
        }

        public function addCard($card) {
            $this->deck[] = $card;
        }
    
        public function fillDeck() {
            $values = array("2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A");
            define('NUMVALS', 13);
            define('VALSPERDECK', 4);
            for($i = 0; $i < $NUMVALS; ++$i) {
                for($k = 0; $k < $VALSPERDECK; ++$k) {
                    $weight = intval($values[$i]);
                    if ($values[$i] == "J" || $values[$i] == "Q" || $values[$i] == "K") {
                        $weight = 10;
                    } else if ($values[i] == "A") {
                        $weight = 11;
                    }
                    $card = array("Value" => $values[$i], "Weight" => $weight,);
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
            $deck2Length = $deck2->length;
            for ($i = 0; $i < $deck2Length; ++$i) {
                $this->deck[] = array_shift($deck2);
            }
        }
    
        function printDeck() {
            print($this->deck);
        }
    }
?>