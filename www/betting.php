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
        $playerID = $player->getPlayerID();
        $row = select("online_user", "money", $playerID, 'playerID');
        $newAmount = $row['money'] + $money;
        $player->setMoney($newAmount);
        update('online_user', 'money', $newAmount, $playerID, 'playerID');
        $player->addWin();
        $_SESSION['sessionPlayer'] = serialize($player);
    }

    // get player's current amount of money
    function getMoney($playerID) {
        $row = select('online_user', 'money', $playerID, 'playerID');
        return $row['money'];
    }
?>