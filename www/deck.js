class Deck {
    constructor() {
        this.deck = []
    }

    getCard() {
        return this.deck.pop();
    }

    addCard(card) {
        this.deck.push(card);
    }

    fillDeck() {
        var values = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"];
        const NUMVALS = 13;
        const VALSPERDECK = 4;
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
                this.deck.push(card);
            }
        }
    }

    shuffleDeck() {
        var deckInLength = this.deck.length;
        var deck1 = [];
        var deck2 = [];
        for(var i = 0; i < deckInLength; ++i) {
            if (i % 2 == 0) {
                deck1.push(this.deck.pop());
            } else {
                deck2.push(this.deck.pop());
            }
        }
        var deck1Length = deck1.length;
        for (i = 0; i < deck1Length; ++i) {
            this.deck.push(deck1.pop());
        }
        var deck2Length = deck2.length;
        for (i = 0; i < deck2Length; ++i) {
            this.deck.push(deck2.pop());
        }
    }

    printDeck() {
        console.log(this.deck);
    }
}

