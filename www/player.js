// data struct for a player
// member variables:
//      name
//      money
//      bet
//      hand (cards)
//      isTurn(?)
//
// functions to add:
//      draw card (hit)
//      stay
//      place bet
//      check if bust
//      calculate hand (if sum < 21 then A is 11, if not 1)
//      changePlayer?
// import "deck.js";

test_deck = [
    {Value: "5", Weight: 5},
    {Value: "A", Weight: 1},
    {Value: "J", Weight: 10},
]

class Player {
    // TODO: Add member variables
    constructor(name) {
        this.name = name;
        this.money = 100;
        this.bet = 0;
        this.hand = [];
        this.is_turn = false;
        this.bust = false;
    }
    

    // Calculates the score of the hand
    calcHand() {
        var sum = 0;
        var num_ace = 0;

        for (var i = 0; i < this.hand.length; i++) {
            if (this.hand[i]["Value"] != "A") {
                sum += this.hand[i]["Weight"];
            }
            else {
                num_ace += 1;
            }
        }

        sum = this.handlesAce(sum, num_ace);
        // sum == 0 means player is bust
        if (sum == 0) {
            return 22;
        }
        return sum;
    }

    // Returns the maximum score with aces that wouldn't bust
    // Returns 0 if player is bust
    handlesAce(current_score, num_ace) {
        if (current_score > 21) {
            return 0;
        }
        if (num_ace == 0) {
            return current_score;
        }
        return(Math.max(this.handlesAce(current_score + 11, num_ace - 1), this.handlesAce(current_score + 1, num_ace - 1)));
    }

    checkBust() {
        if (this.calcHand() > 21) {
            this.bust = true;
        }
    }

    placeBet(betAmount) {
        this.bet += betAmount;
        this.money -= betAmount;
    }

    // Draws a card from the deck and checks if the new hand is bust
    draw(game_deck) {
        var card = game_deck.pop();
        this.hand.push(card);
        
        this.checkBust();
    }
    
}