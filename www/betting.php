<?php
    include 'database.php';

    function takeBet($playerID){
        $row = select(money, online_user, $playerID, 'playerID');
        $newAmount = $row['money'] - 10;
        update(money, online_user, $newAmount, $playerID, 'playerID');
    }

    function addMoney($playerID, $money) {
        $row = select(money, online_user, $playerID, 'playerID');
        $newAmount = $row['money'] + $money;
        update(money, online_user, $newAmount, $playerID, 'playerID');
    }

    function getMoney($playerID) {
        $row = select(money, online_user, $playerID, 'playerID');
        return $row['money'];
    }
?>