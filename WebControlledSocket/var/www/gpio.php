<?php
include('settings.php');
include('include/gpio.inc.php');

if (isset($_GET["pin"]) /*&& isset($_GET["status"])*/)
{
	$pin = strip_tags($_GET["pin"]);
	//$status = strip_tags($_GET["status"]);
	
	$gpio = new Gpio(RELAY_COUNT);

	try
	{
		$outputStatus = null;
		if ($gpio->toggle($pin, $outputStatus))
		{
			die($outputStatus);
		}
	}
	catch (Exception $e)
	{
		die($e->getMessage());
	}
}

die("fail");
?>