<?php
if(isset($_REQUEST['to']))
$to = $_REQUEST['to'];
		$datetime1 = new \DateTime(date('Y/m/d H:i:s',($_COOKIE['start_time'])));
		$datetime2 = new \DateTime(date('Y/m/d H:i:s'));
		$interval = $datetime1->diff($datetime2);
		
		$elapsed = $interval->format('%H:%I:%S');
		echo  $elapsed;
		?>