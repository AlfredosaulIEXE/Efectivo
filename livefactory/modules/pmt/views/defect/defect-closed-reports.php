<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\DatePicker;

$this->title=Yii::t ('app','Defect Closed Report');
date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../include/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
function Add_Error(obj,msg){
	 $(obj).parents('.form-group').addClass('has-error');
	 $(obj).parents('.form-group').append('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_Error(obj){
	$(obj).parents('.form-group').removeClass('has-error');
	$(obj).parents('.form-group').children('.error').remove();
	return false;
}
$(document).ready(function(e) {
   $('.search').click(function(event){
			
				error='';
				Remove_Error($('#end_date'));
				
				var actual_start = $('#start_date').val();
				var actual_end = $('#end_date').val();
				var startTime = new Date(actual_start);
				var endTime = new Date(actual_end);
				if(startTime>endTime){
					 error+=Add_Error($('#end_date'),'<?=Yii::t ('app','End date should not be less than start date!')?>');
				}else{
					Remove_Error($('#end_date'));
				}
				if(error !=''){
					event.preventDefault();
					return false;
				}else{
					return true;
				}
	}) 
});
</script>
<form action="index.php?r=pmt/defect/defect-closed-reports" method="post">
 <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
<div class="row">
	<div class="col-sm-6 col-sm-offset-6">
    	<div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                             <?php
                echo '<label class="control-label">Start Date</label>';echo DatePicker::widget([    'name' => 'start_date','id'=>'start_date',    'type' => DatePicker::TYPE_COMPONENT_PREPEND,    'value' => $_REQUEST['start_date'],    'pluginOptions' => [        'autoclose'=>true,        'format' => 'yyyy-mm-dd'    ]]);
                ?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                       <?php
        echo '<label class="control-label">End Date</label>';echo DatePicker::widget([    'name' => 'end_date', 'id'=>'end_date',   'type' => DatePicker::TYPE_COMPONENT_PREPEND,    'value' => $_REQUEST['end_date'],    'pluginOptions' => [        'autoclose'=>true,        'format' => 'yyyy-mm-dd'    ]]);
        
        ?>
                </div>
            </div>
            <div class="col-sm-2">
                    <label class="control-label" style="color:#fff;"><?=Yii::t ( 'app','Search')?></label>
                <div class="form-group">
                      <input type="submit" value="<?=Yii::t ( 'app','Search')?>" class="btn btn-success search">
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="panel panel-info">
	<div class="panel-heading">
    	<h3 class="panel-title"><?=$this->title?>
        	<div class="pull-right">
            	<a href="index.php?r=pmt/defect/defect-closed-reports&date=last_month" class="btn btn-default btn-xs">
                
<?=Yii::t ( 'app','Last Month')?>
                	
                </a>
                <a href="index.php?r=pmt/defect/defect-closed-reports&date=this_month" class="btn btn-default btn-xs">
<?=Yii::t ( 'app','This Month')?>
                	
                </a>
                <a href="index.php?r=pmt/defect/defect-closed-reports&date=this_week" class="btn btn-default btn-xs">
<?=Yii::t ( 'app','This Week')?>
                	
                </a>
                <a href="index.php?r=pmt/defect/defect-closed-reports&date=yesterday" class="btn btn-default btn-xs">
<?=Yii::t ( 'app','Yesterday')?>
                	
                </a>
                <a href="index.php?r=pmt/defect/defect-closed-reports&date=today" class="btn btn-default btn-xs">
<?=Yii::t ( 'app','Today')?>
                	
                </a>
                
            </div>
        </h3>
    </div>
    <div class="panel-body">
    	<?php
		//var_dump($dataProvider);
		//exit();
		$filter=array('today','yesterday');
	if(!in_array($_GET['date'],$filter)){
		 if($dataProvider){?>
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=Yii::t ( 'app','Defect Closed Report Table')?></h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-stript">
                            <thead>
                            <tr>
                                <th><?=Yii::t ( 'app','User')?></th>
                                <th><?=Yii::t ( 'app','Date')?></th>
                                <th><?=Yii::t ( 'app','Defect Closed')?></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($dataProvider as $row){
                                ?>
                                <tr>
                                    <td><?=$row['first_name']?></td>
                                    <td><?=date('jS \of M Y',strtotime($row['actual_end_datetime']))?></td>
                                    <td><?=$row['counts']?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=Yii::t ( 'app','Defect Closed Report Chart')?></h3>
                    </div>
                    <div class="panel-body">
                        <div id="defectClosed" style="height:350px"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php 
        $users=array();
        $dates=array();
		if(!empty($_REQUEST['start_date']) && !empty($_REQUEST['end_date'])){
			$end_date=date('Y-m-d',strtotime($_REQUEST['end_date']));
			$start_date=date('Y-m-d',strtotime($_REQUEST['start_date']));
		}
		else if($_REQUEST['date']=='last_month'){
			$start_date = date('Y-m-d',strtotime('first day of last month'));
			$end_date = date('Y-m-d',strtotime('last day of last month'));
		}
		else if($_REQUEST['date']=='this_week'){
			$end_date=date('Y-m-d',strtotime('+1 day'));
			$start_date=date('Y-m-d',strtotime('last monday'));
		}else{
			$end_date=date('Y-m-d',strtotime('+1 day'));
			$start_date=date('Y-m-d',strtotime('first day of this month'));
		}
		$end_date= date('Y-m-d',strtotime($end_date. "+1 days"));
		while($start_date != $end_date ){	
			$dates[]=$start_date;
			$start_date= date('Y-m-d',strtotime($start_date. "+1 days"));
			
		}
        foreach($dataProvider as $row){
            //in_
            if(!in_array($row['first_name'],$users)){
                $users[]=$row['first_name'];
            }
            /*if(!in_array($row['actual_end_datetime'],$dates)){
                $dates[]=$row['actual_end_datetime'];
            }*/
            $userCount[$row['actual_end_datetime']][$row['first_name']]=$row['counts'];
            }
            $jSon = "[['Date',";
            $cm='';
                foreach($users as $user){
                    $jSon .=$cm. "'".$user."'";
                    $cm=',';	
                }
            $jSon .= "]";
            foreach($dates as $date){
                    $jSon.= ",['".$date."',";
                    $c='';
                    foreach($users as $user){
                        $count=$userCount[$date][$user]==''?0:floatval($userCount[$date][$user]);
                        $jSon.= $c.$count;
                        $c=',';
                    }
                    $jSon .= "]";
                }
                $jSon.="]";
                //echo $jSon;
                ?>
                
                <script src="../include/jsapi.js"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable(<?=$jSon ?>);
                var options = {
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '75%' },
                isStacked: true,
                fontSize:'10',
              };
            //var chart = new google.visualization.LineChart(document.getElementById('customer-months'));
            var chart = new google.visualization.ColumnChart(document.getElementById('defectClosed'));
            chart.draw(data, options);
            }
            </script><?php
        }else{
			echo Yii::t ( 'app',"no result");	
		}
		
	}else{
		 if($dataProvider){?>
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=Yii::t ( 'app','Defect Closed Report Table')?></h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-stript">
            <thead>
            <tr>
                <th><?=Yii::t ( 'app','User')?></th>
                <th><?=Yii::t ( 'app','Time')?></th>
                <th><?=Yii::t ( 'app','Defect Closed')?></th>
            </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($dataProvider as $row){
                ?>
                <tr>
                    <td><?=$row['first_name']?></td>
                    <td><?=$row['actual_end_datetime'].":00"?></td>
                    <td><?=$row['defectcount']?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=Yii::t ( 'app','Defect Closed Report Chart')?></h3>
                    </div>
                    <div class="panel-body">
                        <div id="defectClosed" style="height:350px"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php 
       			$users=array();
				$dates=array();
				for($i=0;$i<25;$i++){
					$hour=strlen($i)==1?"0".$i.":00":$i.":00";
					$dates[]=$hour;
				}
				//var_dump($dates);
				foreach($dataProvider as $row){
					//in_
					if(!in_array($row['first_name'],$users)){
						$users[]=$row['first_name'];
					}
						$time=$row['actual_end_datetime'].":00";
						$userCount[$time][$row['first_name']]= $row['defectcount'];
				}
				$jSon = "[['Date',";
				$cm='';
					foreach($users as $user){
						$jSon .=$cm. "'".$user."'";
						$cm=',';	
					}
				$jSon .= "]";
					foreach($dates as $date){
						$jSon.= ",['".$date."',";
						$c='';
							foreach($users as $user){
								$count=$userCount[$date][$user]==''?0:$userCount[$date][$user];
								$jSon.= $c.$count;
								$c=',';
							}
					$jSon .= "]";
				}
				$jSon.="]";
            //  echo $jSon;
                ?>
                
                <script src="../include/jsapi.js"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable(<?=$jSon ?>);
                var options = {
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '75%' },
                isStacked: true,
                fontSize:'10',
              };
            //var chart = new google.visualization.LineChart(document.getElementById('customer-months'));
            var chart = new google.visualization.ColumnChart(document.getElementById('defectClosed'));
            chart.draw(data, options);
            }
            </script><?php
        }else{
			echo Yii::t ( 'app',"no result");	
		}
	}
		 ?>
    </div>
</div>

