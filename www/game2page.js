src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"

//function to check the number of players in the game to see if game should start
function checkPlayers() {
    $.get('getNumPlayers.php', function(response) {
        let numPlayers = parseInt(response.match(/(\d+)/));
        if (numPlayers >= 3) {
            document.getElementById("gameStart").innerHTML = "3 players have joined. Starting game.";
            console.log("numPlayers is 3");
            //clearInterval(checkStart);
        }
        else {
            console.log("numPlayers is less than 3");
            document.getElementById("hitBtn").disabled = true;
            document.getElementById("stayBtn").disabled = true;
        }
    })
}

//function to check the current player of the game to see if your buttons should be enabled
function checkCurrentPlayer(playerID) {
    $.get('getCurrentPlayer.php', function(response) {
        let currentPlayer = response;
        console.log("Current player is: " + playerID);
        if (currentPlayer == playerID) {
            console.log("It's your turn");
            document.getElementById("currentPlayer").innerHTML = "IT'S YOUR TURN";
        }
        else {
            console.log("It's not your turn");
            document.getElementById("hitBtn").disabled = true;
            document.getElementById("stayBtn").disabled = true;
        }
    })
}
