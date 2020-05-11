<?php
    session_start();
    include_once "session.php";

    function mainPlayerHand() {
        // POST request behavior here so session/db would get update before interfacing
//        if (isset($_POST['reset'])) {
//            resetGame();
//        }
        $_SESSION['active_time'] = 0;
        $_SESSION['is_btn_disabled'] = false;

        if (isset($_POST['hit'])) {
            hit();
        }

        if (isset($_POST['stay'])) {
            incrementTurn();
        }

//        checkNewRound();

        // Prints hand
        //printHand($player);
        printHandNew();
        $player = unserialize($_SESSION['sessionPlayer']);

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
            $_SESSION['is_btn_disabled'] = true;
        }
        isLoginSessionExpired();
    }

    function otherPlayerHand() {
        // Get all other players in room 1
        $_SESSION['active_time'] = 0;
        $_SESSION['is_btn_disabled'] = true;
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
                $otherPlayer = new Player("other");

                if ($cards_query->num_rows > 0) {
                    while($card_row = $cards_query->fetch_assoc()) {
                        $otherPlayer->addCardByID($card_row['cardID']);
                    }
                }
                // Prints hand and score
                $otherHand = $otherPlayer->getHand();
                for ($i = 0; $i < count($otherHand); $i++) {
                    $cardVal = $otherHand[$i]['Value'];
                    echo "<div class='card'>$cardVal</div>";
                }
                //echo "Score: " . $otherPlayer->calcHand();
                $money = $row['money'];
                echo " Money: $money";
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

    function getDealer() {
        $dealer = new Dealer("dealer");
        $dealerID = getDealerID();
        $conn = makeConnection();
        $sql = "SELECT * from card_hand WHERE handID = '$dealerID'";
        $cards_query = $conn->query($sql);

        // Add all cards to dealer obj
        if ($cards_query->num_rows > 0) {
            while($card_row = $cards_query->fetch_assoc()) {
                $dealer->addCardByID($card_row['cardID']);
            }
        }
        return $dealer;
    }

    function dealerHand() {
        $dealer = getDealer();
        $row = select('game', 'dealerHidden', 'gameID', 1);
        $hidden = $row['dealerHidden'];
        // Prints hand and score
        $dealerHand = $dealer->getHand();
        for ($i = 0; $i < count($dealerHand); $i++) {
            $cardVal = $dealerHand[$i]['Value'];
            if ($hidden == 1 && $i == 1) {
                echo "<div class='card'>X</div>";
            }
            else {
                echo "<div class='card'>$cardVal</div>";
            }

        }
        //echo "Score: " . $dealer->calcHand();
    }
?>