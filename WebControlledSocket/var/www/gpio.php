<?php
include('settings.php');

if (isset($_GET["pin"]) && isset($_GET["status"]))
{
	$pin = strip_tags($_GET["pin"]);
	$status = strip_tags($_GET["status"]);
	
	$toggleValues = array('0' => '1', '1' => '0');

	if (is_numeric($pin) && is_numeric($status) && 
		$pin <= $relayCount && $pin >= 0 && 
		$status == "0" || $status == "1")
	{
		$newStatus = $toggleValues[$status];
		
		system("gpio mode ".$pin." out");
		system("gpio write ".$pin." ".$newStatus);
		
		echo($newStatus);
	}
	else
	{
		echo("fail");
	}
}
else
{
	echo ("fail");
}
?>