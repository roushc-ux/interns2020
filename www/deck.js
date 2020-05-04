
var values = ["2", "3", "4", "5", "6", "7", "8", "9", "J", "Q", "K", "A"];
var deck = new Array();
const NUMVALS = 13;
const VALSPERDECK = 4;

function createDeck() {
    for(var i = 0; i < NUMVALS; ++i) {
        for(var k = 0; k < VALSPERDECK; ++k) {
            if (values[i] == "J" || values[i] == "Q" || values[i] == "K") {
                var card = {Value: values[i], Weight: 10};
                deck.push(card);
            } else if (values[i] == "A") {
                var card = {Value: values[i], Weight: 11};
                deck.push(card);
            } else {
                var card = {Value: values[i], Weight: parsInt(values[i])};
                deck.push(card);
            }
        }
    }
}

function shuffleDeck(deck) {

}
