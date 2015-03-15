<?php include('settings.php'); ?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Control Panel</title>
		<script type="text/javascript">
			function ajaxToggle(sender, pin, status)
			{
				var request = new XMLHttpRequest();
				request.open("GET", "gpio.php?pin=" + pin + "&status=" + status);
				request.send(null);
				
				request.onreadystatechange = function()
				{
					if (request.readyState == 4 && request.status == 200)
					{
						var status = parseInt(request.responseText);
						sender.alt = "" + status;
						sender.src = ~sender.src.indexOf('green') ? "image/red/red_" + pin + ".jpg" : "image/green/green_" + pin + ".jpg";
					}
					else if (request.readyState == 4 && request.status == 500)
					{
						alert ("server error");
					}
				};
			}
		
			function onClick(sender)
			{
				var pin = sender.id.slice(-1);
				var value = parseInt(sender.alt);

				ajaxToggle(sender, pin, value);
			}
		</script>
    </head>
    <body style="background-color: black;">
	<?php
	function renderButton($i, $outputStatus)
	{
		$image = 'image/';
		$image .= $outputStatus == 1 ? 'red/red_' : 'green/green_';
		$image .= $i.'.jpg';
		
		echo ('<img id="button'.$i.'" src="'.$image.'" alt="'.$outputStatus.'" onClick="javascript:onClick(this);" />');
	}
	
	for ($i = 0; $i < $relayCount; $i++)
	{
		$outputStatus = null;
		system("gpio mode ".$i." out");
		exec("gpio read ".$i, $outputStatus);
		
		renderButton($i, $outputStatus[0]);
	}
	?>
    </body>
</html>