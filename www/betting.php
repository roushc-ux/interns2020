<?php
    include_once 'database.php';
    include_once '';

    // remove bet amount from players
    function takeBet($player){
        $playerID = $player->getPlayerID();
        $row = select("online_user", "money", $playerID, 'playerID');
        $newAmount = $row['money'] - 10;
        // if the player doesn't have enough money to bet, kick them from the game
        if ($newAmount < 0) {
            leave_game($player->getName());
        }
        else {
            update("online_user", "money", $newAmount, $playerID, 'playerID');
        }
    }

    // give award money to player
    function addMoney($player, $money) {
        $playerID = $player->getPlayerID();
        $row = select("online_user", "money", $playerID, 'playerID');
        $newAmount = $row['money'] + $money;
        update('online_user', 'money', $newAmount, $playerID, 'playerID');
        $player->addWins();
    }

    // get player's current amount of money
    function getMoney($playerID) {
        $row = select('online_user', 'money', $playerID, 'playerID');
        return $row['money'];
    }
?>