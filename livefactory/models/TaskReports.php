<?php

namespace livefactory\models;
use livefactory\models\AssignmentHistory;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\Task as TaskModel;
use livefactory\models\TaskStatus;
class TaskReports extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
	public function init(){

	}
    public static  function myTaskStatusChart($id){
	$query = TaskModel::find()->select("tbl_task_status.label,count(tbl_task.id) statuscount")->joinWith('taskStatus')->groupBy('task_status_id')->where("user_assigned_id=".Yii::$app->user->identity->id)->asArray()->all();

$xaxis = "[['Task ', 'Task Status']";
foreach ($query as $row_task_status) 
{
	$xaxis.=",['".$row_task_status['label']."', ".intval($row_task_status['statuscount'])."]";
}
$xaxis.="]";
//echo $xaxis;
?>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?=$xaxis?>);

        var options = {
          title: '',
		 pieHole: 0.3,
        };

        var chart = new google.visualization.PieChart(document.getElementById('<?=$id?>'));
        chart.draw(data, options);
      }
    </script>
<?php }
public static  function taskStatusChart($id){
	$query = TaskModel::find()->select("tbl_task_status.label,count(tbl_task.id) statuscount")->joinWith('taskStatus')->groupBy('tbl_task_status.label')->asArray()->all();

$xaxis = "[['Task ', 'Task Status']";
foreach ($query as $row_task_status) 
{
	$xaxis.=",['".$row_task_status['label']."', ".intval($row_task_status['statuscount'])."]";
}
$xaxis.="]";
//echo $xaxis;
?>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?=$xaxis?>);

        var options = {
          title: '',
		 pieHole: 0.3,
		 colors: ['#ed5565', '#1c84c6', '#1ab394', '#d1dade']
        };

        var chart = new google.visualization.PieChart(document.getElementById('<?=$id?>'));
        chart.draw(data, options);
      }
    </script>
<?php }
	
	
	public static  function taskStatusCounting($user,$type){
		$taskTypes = TaskModel::find()->select("tbl_task_status.label,count(tbl_task.id) statuscount")->joinWith('taskStatus')->groupBy('tbl_task_status.label')->where("user_assigned_id=".$user." and task_status_id=".$type)->asArray()->one();
		return $taskTypes['statuscount']==''?0:$taskTypes['statuscount'];
	}
	
	public function getTaskAssignmentChart($id){
		$users = TaskModel::find()->joinWith('user')->asArray()->all();
		$user2d=array();
		//user array
		foreach($users as $usrs){
				if(!empty($usrs['user']['id']))
				$user2d[$usrs['user']['id']]=$usrs['user']['first_name']." ".$usrs['user']['last_name'];
		}
		// task status
		$taskTypes = TaskModel::find()->select("tbl_task_status.label,tbl_task_status.id")->joinWith('taskStatus')->asArray()->all();
$jSon = "[['User',";
$cm='';
	foreach($taskTypes as $status){
		$jSon .=$cm. "'".$status['label']."'";
		$cm=',';	
	}
$jSon .= "]";
		foreach($user2d as $key => $user){
			$jSon.= ",['".$user."',";
			$c='';
			foreach($taskTypes as $status){
				$jSon.= $c.$this->taskStatusCounting($key,$status['id']);
				$c=',';
			}
			$jSon .= "]";
		}
		$jSon.="]";
		//var_dump($jSon);
	?>
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
		colors: ['#ed5565', '#1c84c6', '#1ab394', '#d1dade'],
      };
	  
	 
	//var chart = new google.visualization.LineChart(document.getElementById('customer-months'));
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$id?>'));
	chart.draw(data, options);
	}
    </script>
  <?php }
  public static  function getTimeDiff($start,$end){
		$hours=$minutes=0;
		$timing=explode(',',TimeDiffModel::dateDiff($end,$start));
		foreach($timing as $value){
			if(strpos($value,'day') !== false){
				$day=trim(str_replace('day','',$value));
			}
			if(strpos($value,'hours') !== false){
				$hours=trim(str_replace('hours','',$value));
			}
			if(strpos($value,'minutes') !== false){
				$minutes=trim(str_replace('minutes','',$value));
			}
			if(strpos($value,'minute') !== false){
				$minutes=trim(str_replace('minute','',$value));
			}
		}
		$hours = ($day*24)+$hours;
		return $hours.".".$minutes;
	}
  public static  function getTotalUserSpentTime($uid,$date){
	  $sql="select tbl_time_entry.* from tbl_time_entry,tbl_task where tbl_task.user_assigned_id='$uid' and tbl_task.id=tbl_time_entry.entity_id and date(start_time)='$date'";
	  $connection = \Yii::$app->db;
	  $command=$connection->createCommand($sql);
	  $dataReader=$command->queryAll();
					
		$spend_t=0;
		$dotNumTot=0;
		$solidNumTot=0;
		foreach($dataReader as $trow1){
			list($solidNum,$dotNum) = explode('.',$this->getTimeDiff(date('Y/m/d H:i:s',strtotime($trow1['start_time'])),date('Y/m/d H:i:s',strtotime($trow1['end_time']))));
			$solidNumTot+=$solidNum;
			$dotNumTot+=$dotNum;
		}
		list($plusNum)=explode('.',$dotNumTot/60);;
		$dotVal=round($dotNumTot%60);
		$solidNum =$solidNumTot+$plusNum;
		$dotVal=strlen($dotVal)==1?"0".$dotVal:$dotVal;
		$solidNum=strlen($solidNum)==1?"0".$solidNum:$solidNum;
		$spend_t=$solidNum.".".$dotVal;
  }
  /*public static  function getTaskCloseChart($start,$end){
	  
  }*/
}
