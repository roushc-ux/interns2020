<?php
include_once 'database.php';
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

    public function getHand() {
        return $this->hand;
    }

    public function isBust() {
        return $this->bust;
    }

    public function isTurn() {
        return $this->is_turn;
    }

    public function getName() {
        return $this->name;
    }

    // Calculates the score of the hand
    public function calcHand() {
        if (count($this->hand) == 0) {
            return 0;
        }

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
        $sum = $this->handlesAce($sum, $num_ace);

        // sum == 0 from handsAce means player bust
        // recalc sum
        if ($sum == 0) {
            for ($i = 0; $i < count($this->hand); $i++) {
                $sum += $this->hand[$i]["Weight"];
            }
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
        return $this->bust;
    }

    public function numCards() {
        return count($this->hand);
    }

    public function placeBet($betAmount) {
        $this->bet += $betAmount;
        $this->money -= $betAmount;
    }

    public function drawCard($game_deck) {
        $this->hand[] = $game_deck->getCard();
        
        $this->checkBust();
    }

    public function addCard($card) {
        $this->hand[] = $card;
    }

    public function addCardByID($id) {
        $cardValMap = deckArray();
        $card = $cardValMap[$id];
        $this->hand[] = $card;
    }

    public function emptyHand() {
        $this->hand = [];
        $this->bust = false;
    }

    public function getPlayerID() {
        $row = select('online_user', 'playerID', 'username', $this->name);
        return $row['playerID'];
    }

    public function addWin() {
        $row = select('user', 'wins', $this->getName(), 'username');
        $newWins = $row['wins'] + 1;
        update('user', 'wins', $newWins, $this->getName(), 'username');
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