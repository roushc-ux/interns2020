// funcs we prolly need
//      startGame
//      deal cards
//      change player (something to simulate a player's turn)
//          checks if human or dealer
//          human: do stuff till they bust or stay (while loop here?)
//      calc result (money gain/lose) NOTE: How to set private member vars?
//      accumulate and add cards to the discard deck at the end of the game


function startGame() {
    let deck = new Deck();
    let players = [];
    let dealer = new dealer();
    let discardDeck = new Deck();
}

function dealCards(players, dealer, game_deck) {
    for (let i = 0; i < 2; i++) {
        for (let j = 0; j < players.length; j++) {
            players[j].draw(game_deck);
        }
        dealer.draw(game_deck);
    }
}

function playerMove() {

}

function tally(players, dealer) {
    if (dealer.bust) {
        for (let i = 0; i < players.length; i++) {
            
        }
    } 
}

function addToDiscard() {
    for (let i=0; i<dealer.hand.length; i++) {
        discardDeck.addCard(dealer.hand[i]);
    }
    for (let i=0; i<3; i++) {
        for (let j=0; j<player[i].hand.length; j++) {
            discardDeck.addCard(player[i].hand[j]);
        }
    }
}