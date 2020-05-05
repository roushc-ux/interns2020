<?php

class Player {
    private $name;
    private $money;
    private $bet;
    private $hand;
    private $is_turn;
    private $bust;


    public function __construct($nameIn) {
        $this->name = $nameIn;
        $this->money = 100;
        $this->bet = 0;
        $this->hand = [];
        $this->is_turn = False;
        $this->bust = False;
    }

    // Calculates the score of the hand
    public function calcHand() {
        $sum = 0;
        $num_ace = 0;

        for ($i = 0; $i < count($this->hand); $i++) {
            if ($this->hand[$i]["Value"] != "A") {
                $sum += $this->hand[$i]["Weight"];
            }
            else {
                $num_ace += 1;
            }
        }

        $sum = $this->handlesAce(sum, num_ace);
        // sum == 0 means player is bust
        if ($sum == 0) {
            return 22;
        }
        return $sum;
    }

    // Returns the maximum score with aces that wouldn't bust
    // Returns 0 if player is bust
    public function handlesAce($current_score, $num_ace) {
        if ($current_score > 21) {
            return 0;
        }
        if ($num_ace === 0) {
            return $current_score;
        }
        return(max($this->handlesAce($current_score + 11, $num_ace - 1), $this->handlesAce($current_score + 1, $num_ace - 1)));
    }
    
    public function checkBust() {
        if ($this->calcHand() > 21) {
            $this->bust = True;
        }
    }

    public function placeBet($betAmount) {
        $this->bet += $betAmount;
        $this->money -= $betAmount;
    }

    public function drawCard($game_deck) {
        $this->hand->push($game_deck->getCard());
        
        $this->checkBust();
    }    
}

class Dealer extends Player {
    public function __constructor() {
        parent::__construct("Dealer");
    }

    // Dealer only draws if hand < 16
    public function move($game_deck) {
        if ($this->calcHand() < 16) {
            $this->draw($game_deck);
        }
        $this->checkBust();
    }
}

?>