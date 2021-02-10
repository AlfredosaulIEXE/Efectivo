<?php

$this->title = Yii::t ( 'app','My Calendar');

$jSon="[";

$coma='';

foreach($dataProvider as $row){

	$color = '';
	if ($row['due_date'] != '')
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$row['due_date']=date('Y/m/d h:i:s', $row['due_date']);
	}
	$jSon.=$coma."{'id':'".$row['id']."','color':'".$color."','title':'".addslashes($row['ticket_title'])."','start':'".$row['due_date']."','end':'".$row['due_date']."','url':'index.php?r=support/ticket/update&id=".$row['id']."'}";

	$coma=",";

 } 

$jSon.="]"; 

//echo $jSon;

 ?>

 <script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<link href='../include/calendar/fullcalendar.css' rel='stylesheet' />

<link href='../include/calendar/fullcalendar.print.css' rel='stylesheet' media='print' />

 <script src='../include/calendar/lib/moment.min.js'></script>

<script>



	$(document).ready(function() {

		

		$('#calendar').fullCalendar({

			header: {

				left: 'prev,next today',

				center: 'title',

				right: 'month,agendaWeek,agendaDay'

			},

			editable: true,

			eventLimit: true, // allow "more" link when too many events

			events:<?=$jSon?>

		});

		

	});



</script>

<div class="panel panel-info">

	<div class="panel-heading">

    	<h3 class="panel-title"><?=$this->title?></h3>

    </div>

    <div class="panel-body">

    	<div id='calendar'></div>

    </div>

</div>

	

