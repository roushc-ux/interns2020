<?php
    
    class Deck {
        private $deck;
        private $deckNum;
        
        
        public function __construct() {
            $this->deck = array();
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
    }
?>