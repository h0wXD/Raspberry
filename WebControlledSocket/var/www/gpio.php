<?php
include('settings.php');
include('include/GpioManager.inc.php');
include('include/RelayStateManager.inc.php');

if (isset($_GET["pin"]))
{
	$pin = strip_tags($_GET["pin"]);
	$gpioManager = new GpioManager(RELAY_COUNT);
	$relayStateManager = new RelayStateManager(RELAY_STATE_FILE);

	try
	{
		$outputStatus = null;
		if ($gpioManager->toggle($pin, $outputStatus))
		{
			$relayStateManager->setState($pin, $outputStatus);
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