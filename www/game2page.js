function checkPlayers() {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let numPlayers = parseInt(this.response.match(/(\d+)/));
            // console.log(typeof(numPlayers));
            console.log(numPlayers);
            if (numPlayers >= 3) {
                document.getElementById("gameStart").innerHTML = "3 players have joined. Starting game.";
                console.log("numPlayers is greater than 3");
                clearInterval(checkStart);
            }
            else {
                document.getElementById("hitBtn").disabled = true;
                document.getElementById("stayBtn").disabled = true;
            }
        }
    }
    xmlhttp.open("GET", "getNumPlayers.php", true);
    xmlhttp.send();
}

function checkCurrentPlayer(playerID) {
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let currentPlayer = this.response;
            //console.log("current player");
            //console.log(currentPlayer);
            //console.log("player ID");
            //console.log(playerID);
            if (currentPlayer == playerID) {
                document.getElementById("currentPlayer").innerHTML = "IT'S YOUR TURN";
            }
            else {
                document.getElementById("hitBtn").disabled = true;
                document.getElementById("stayBtn").disabled = true;
            }
        }
    }
    xmlhttp.open("GET", "getCurrentPlayer.php", true);
    xmlhttp.send();
}