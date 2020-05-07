<?php
    session_start();
    include "game_mech.php";
    include "session.php";

    function mainPlayerHand() {
        getSessionHandID();
        getSessionDeckID();

        // Keep track of player info within session
        if (!isset($_SESSION['sessionPlayer'])) {
            // $player = new Player("name");
            $username = $_SESSION['login_user'];
            $player = new Player ($username);
            $_SESSION['sessionPlayer'] = serialize($player);
        }

        // POST request behavior here so session/db would get update before interfacing
        if (isset($_POST['reset'])) {
            resetGame();
        }

        if (isset($_POST['hit'])) {
            hit();
        }

        // Prints hand
        $player = unserialize($_SESSION['sessionPlayer']);
        printHand($player);

        // if (!$player->isTurn() or $player->isBust()) {
        if ($player->isBust()) {
            echo "Bust you're out";
            echo "<script type='text/javascript'>
            $(document).ready(function()
            {
            $('#hitBtn').prop('disabled', true);
            $('#stayBtn').prop('disabled', true);
            });
        </script>";
        }
    }

    function otherPlayerHand() {
        // Get all other players in room 1
        $conn = makeConnection();
        $currPlayerHandID = $_SESSION["sessionHandID"];
        $sql = "SELECT * FROM online_user WHERE gameID = 1 AND handID <> '$currPlayerHandID'";
        $result = $conn->query($sql);

        // Divs for each player
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='player'><div class='card-box'>";
                // Get the player's hand
                $handID = $row["handID"];
                $sql = "SELECT * from card_hand WHERE handID = '$handID'";
                $cards_query = $conn->query($sql);

                if ($cards_query->num_rows > 0) {
                    while($card_row = $cards_query->fetch_assoc()) {
                        $cardValMap = deckArray();
                        $card = $cardValMap[$card_row['cardID']];
                        $cardValue = $card['Value'];
                        echo "<div class='card'>$cardValue</div>";
                    }
                }
                $username = $row["username"];
                echo "</div><div class='card'>$username</div></div>";
            }
        }
//        for ($i = 0; $i < $numPlayers - 1; $i++) {
//            $playerIdx = $i + 2;
//
//            // Get the player's hand
//            $sql = "SELECT card_hand.* FROM online_user
//                    INNER JOIN card_hand on online_user.handID = card_hand.handID
//                    WHERE online_user.gameID = 1";
//            $result = $conn->query($sql);
//
//            echo "
//            <div class='player'>
//                <div class='card-box'></div>
//                <div class='card'>Player $playerIdx</div>
//            </div>
//            ";
//        }

        $conn->close();
    }
?>