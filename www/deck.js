
var values = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"];
var deck = new Array();
const NUMVALS = 13;
const VALSPERDECK = 4;

function createDeck() {
    for(var i = 0; i < NUMVALS; ++i) {
        for(var k = 0; k < VALSPERDECK; ++k) {
            var weight = parseInt(values[i])
            if (values[i] == "J" || values[i] == "Q" || values[i] == "K") {
                weight = 10
            } else if (values[i] == "A") {
                var card = {Value: values[i], Weight: 11};
                weight = 11;
            }
            var card = {Value: values[i], Weight: weight};
            deck.push(card);
        }
    }
}

function shuffleDeck(deckIn) {
    var deckInLength = deckIn.length;
    var deck1 = new Array();
    var deck2 = new Array();
    for(var i = 0; i < deckInLength; ++i) {
        if (i % 2 == 0) {
            deck1.push(deckIn.pop());
        } else {
            deck2.push(deckIn.pop());
        }
    }
    var deck2Length = deck2.length;
    for (i = 0; i < deck2Length; ++i) {
        deck1.push(deck2.pop());
    }
    return deck1;
}

function printDeck(deckIn) {
    console.log(deckIn);
}
