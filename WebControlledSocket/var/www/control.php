<?php include('settings.php'); include('include/GpioManager.inc.php'); ?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Control Panel</title>
		<script type="text/javascript">
			function ajaxToggle(sender, pin, status)
			{
				sender.src = "image/gray/button" + pin + ".jpg";
			
				var request = new XMLHttpRequest();
				request.open("GET", "gpio.php?pin=" + pin + "&status=" + status);
				request.send(null);
				
				request.onreadystatechange = function()
				{
					if (request.readyState == 4 && request.status == 200)
					{
						var status = parseInt(request.responseText);
						var color = status == <?php echo STATUS_OFF; ?> ? "red" : "green";
						
						sender.alt = "" + status;
						sender.src = "image/" + color + "/button" + pin + ".jpg";
					}
					else if (request.readyState == 4 && request.status == 500)
					{
						alert ("server error");
					}
				};
			}
		
			function onClick(sender)
			{
				if (~sender.src.indexOf('gray'))
				{
					return;
				}
				
				var pin = sender.id.slice(-1);
				var value = parseInt(sender.alt);
				
				ajaxToggle(sender, pin, value);
			}
		</script>
    </head>
    <body style="background-color: black;">
	<?php
	function renderButton($pin, $outputStatus)
	{
		$color = $outputStatus == STATUS_OFF ? 'red' : 'green';
		$image = sprintf('image/%s/button%s.jpg', $color, $pin);

		echo ('<img id="button'.$pin.'" src="'.$image.'" alt="'.$outputStatus.'" onClick="javascript:onClick(this);" />');
	}
	
	$gpio = new GpioManager(RELAY_COUNT);
	
	for ($pin = 0; $pin < RELAY_COUNT; $pin++)
	{
		$outputStatus = $gpio->read($pin);

		renderButton($pin, $outputStatus[0]);
	}
	?>
    </body>
</html>