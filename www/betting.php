<?php

    include_once 'database.php';
    include_once 'player.php';

    // remove bet amount from players
    function takeBet(){
        $playerID = getPlayerID();
        $player = unserialize($_SESSION['sessionPlayer']);
        $row = select("online_user", "money", $playerID, 'playerID');
        $conn = makeConnection();
        $newAmount = $row['money'] - 10;
        // if the player doesn't have enough money to bet, kick them from the game
        if ($newAmount < 0) {
            //leave_game($player->getName());
        }
        else {
            $player->setMoney($newAmount);
            $sql = "UPDATE online_user SET money = $newAmount WHERE $playerID = playerID";
            $conn->query($sql);
            $_SESSION['sessionPlayer'] = serialize($player);
        }
    }

    // give award money to player
    function addMoney($player, $money) {
        $playerID = getPlayerID();
        $conn = makeConnection();
        $sql = "SELECT money FROM online_user WHERE '$playerID' = playerID";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result);
        $newAmount = $row['money'] + $money;
        $player->setMoney($newAmount);
        $conn = makeConnection();
        $sql = "UPDATE online_user SET money = '$newAmount' WHERE '$playerID' = playerID";
        $conn->query($sql);
        $player->addWin();
        $_SESSION['sessionPlayer'] = serialize($player);
    }

    // get player's current amount of money
    function getMoney($playerID) {
        $row = select('online_user', 'money', $playerID, 'playerID');
        return $row['money'];
    }
?>