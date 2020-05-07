<!--Helper functions that might (not) be helpful-->

<?php
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

?>