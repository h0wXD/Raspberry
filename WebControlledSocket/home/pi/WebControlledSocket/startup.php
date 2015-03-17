<?php

include ('/var/www/settings.php');
include ('/var/www/include/GpioManager.inc.php');
include ('/var/www/include/RelayStateManager.inc.php');

$relayStateManager = new RelayStateManager(RELAY_STATE_FILE);
$gpioManager = new GpioManager(RELAY_COUNT);

echo("Checking previous state for a total of ".RELAY_COUNT." pins\n");

for ($pin = 0; $pin < RELAY_COUNT; $pin++)
{
	$savedState = $relayStateManager->getState($pin);
	$currentState = $gpioManager->read($pin);
	
	if ($savedState != $currentState)
	{
		echo("Updated pin ".$pin." state to ".$savedState."\n");
		$gpioManager->write($pin, $savedState);
	}
}

echo("Check completed.\n");

?>