<?php
use livefactory\models\TaskStatus;

$this->title = Yii::t ( 'app','My Calendar');

$jSon="[";

$coma='';

foreach($dataProvider as $row){
	//print_r($row);
	if(($row['expected_end_datetime']) < time() and $row['task_status_id'] !=TaskStatus::_COMPLETED){

		$color='#F00';	

	}else if($row['task_status_id'] ==TaskStatus::_COMPLETED){

		$color='#090';	

	}else{

		$color='#F90';

	}
	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
	$row['expected_end_datetime'] = date('Y-m-d H:i:s', $row['expected_end_datetime']);
	$row['expected_start_datetime'] = date('Y-m-d H:i:s', $row['expected_start_datetime']);

	$jSon.=$coma."{'id':'".$row['id']."','color':'".$color."','title':'".addslashes($row['task_name'])."','start':'".$row['expected_start_datetime']."','end':'".$row['expected_end_datetime']."','url':'index.php?r=pmt/task/task-view&id=".$row['id']."'}";

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

	

