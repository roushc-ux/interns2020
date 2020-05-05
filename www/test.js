$(document).ready(function(){
    $("button").click(function(){
        $.get("/sql/data.sql", function(data, status){
            alert("Data: " + data + "\nStatus: " + status);
        });
    })

    $("#newDeck").click(function(){
        var deck = new Deck();
        deck.fillDeck();
        deck.printDeck();
    })
});