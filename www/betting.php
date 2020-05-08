<?php
    include_once 'database.php';

    function takeBet($player){
        $playerID = $player->getPlayerID();
        $row = select(money, online_user, $playerID, 'playerID');
        $newAmount = $row['money'] - 10;
        if ($newAmount < 0) {
            
        }
        else {
            update(money, online_user, $newAmount, $playerID, 'playerID');
        }
    }

    function addMoney($player, $money) {
        $playerID = $player->getPlayerID();
        $row = select(money, online_user, $playerID, 'playerID');
        $newAmount = $row['money'] + $money;
        update(money, online_user, $newAmount, $playerID, 'playerID');
        $player->addWins();
    }

    function getMoney($playerID) {
        $row = select(money, online_user, $playerID, 'playerID');
        return $row['money'];
    }
?>