<?php
	//include 'config.php';
	//echo $_POST['command'];

	function battle($attackers, $defenders){
		echo "starting battle: ".$attackers." attackers vs ".$defenders." defenders. <br><br>"; 
		$matches = 2;
		$over = false;
		$attDices = array();
		$defDices = array();
		$round = 0;
		while (!$over){
			$round++;
			//get the number of dices per player
			$att = $attackers < 3 ? $attackers : 3;
			$def = $defenders < 2 ? $defenders : 2;
			echo "round ".$round." a:".$att."(".$attackers.") d:".$def."(".$defenders.")<br>";
			//throw dices
			for ($x = 0; $x < $att; $x++){
				$attDices[$x] = rand(1,6);
			}
			for ($x = 0; $x < $def; $x++){
				$defDices[$x] = rand(1,6);
			}
			//sort dices
			rsort($attDices);
			rsort($defDices);
			//get number of rounds
			if ($def < $matches) {
				$matches = $def;
			}
			if ($att < $matches) {
				$matches =  $att;
			}
			//pair results
			for ($x = 0; $x < $matches; $x++){
				echo "a:".$attDices[$x]." d:".$defDices[$x]." ";
				if ($attDices[$x] > $defDices[$x]){
					$defenders--;
					echo "attacker wins<br>";
				} else {
					$attackers--;
					echo "defender wins<br>";
				}
			}
			//check if battle is over
			if ($attackers == 0){
				echo "<br>DEFENDER WINS BATTLE (".$defenders." remaining)";
				$over = true;
			}
			if ($defenders == 0){
				echo "<br>ATTACKER WINS BATTLE (".$attackers." remaining)";
				$over = true;
			}
		} //while !over
		return array (a => intval($attackers), d => intval($defenders));
	}

	function attack($from, $to, $using) {
		if (($from-$to) > 1 || ($to-$from) < -1) {
			echo "<b>Error:</b> <i>from</i> and <i>to</i> rows must be adjacent.";
			exit();
		}
		$con = $GLOBALS['con'];
		$query = "SELECT * FROM rows WHERE id=".$from;
		$queryResult = mysqli_query($con, $query);
		$fromRow = mysqli_fetch_assoc($queryResult);
		//TODO check if $fromRow belongs to current user
		if ($fromRow['army'] < 2){
			echo "<b>Error:</b> You need at least 2 army units in <i>from</i> row to attack. You have ".$fromRow['army'];
			exit();
		}
		if ($using === null) {
			$using = $fromRow['army'] - 1;
		}
		if ($using > ($fromRow['army'] - 1)){
			echo "<b>Error:</b> <i>using</i> quantity can't be greater than ".($fromRow['army'] - 1)." (army count - 1).";
			exit();
		}
		$query = "SELECT * FROM rows WHERE id=".$to;
		$queryResult = mysqli_query($con, $query);
		$toRow = mysqli_fetch_assoc($queryResult);
		//TODO check if $toRow doesn't belong to current user
		$battleResult = battle($using,$toRow['army']);
		//update attacker row
		$query = "UPDATE rows SET army = army - ".$using." WHERE id = ".$from;
		mysqli_query($con, $query);
        echo mysqli_error($con);
        //set defender as winner
        $winnerUser = $toRow['user_id'];
        $winnerArmy = $battleResult['d'];
        //if attacker won, change winner
        if ($battleResult['d'] == 0) {
        	$winnerUser = $fromRow['user_id'];
        	$winnerArmy = $battleResult['a'];
        }
		//update defender row
		$query = "UPDATE rows SET army = ".$winnerArmy.", user_id = ".$winnerUser." WHERE id = ".$to;
		mysqli_query($con, $query);
        echo mysqli_error($con);
	}
	
	switch ($_GET['action']){
		case 'battle' : battle($_GET['a'],$_GET['d']); 
		case 'attack' : attack($_GET['from'],$_GET['to'],$_GET['using']);
	}
	
?>