<?php

namespace livefactory\models;
use livefactory\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class UserReports extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
	public function userTypeChart($id){ 
	$sql = "SELECT t.label,count(user.id) typecount from tbl_user user,tbl_user_type  t where t.id=user.user_type_id 
	GROUP BY t.label";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	$xaxis = "[['User Type', 'User Type']";
	foreach($dataReader as $row_user_type) 
	{
		$xaxis.=",['".$row_user_type['label']."', ".intval($row_user_type['typecount'])."]";
	}
	$xaxis.="]";
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
	function userStatusChart($id){
		$sql = "SELECT user.active,count(user.id) typecount from tbl_user user  
	GROUP BY active";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	
	$xaxis = "[['User Active', 'User Active']";
	foreach($dataReader as $row_user_active) 
	{
		$active=$row_user_active['active']==1?'Active':'Deactive';
		$xaxis.=",['".$active."', ".intval($row_user_active['typecount'])."]";
	}
	$xaxis.="]";
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
	public function getTotalUser($month,$year){
	$query_user = "SELECT * FROM tbl_user WHERE from_unixtime(added_at, '%m') ='$month' and from_unixtime(added_at, '%Y') ='$year'";
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($query_user);
	$dataReader=$command->queryAll();
	return count($dataReader);
	}
	public function newUserChart($id){
	$month = time();
		for ($i = 1; $i <= 12; $i++) {
			if($i==1)
		  $month = strtotime(date('M', $month), $month);
		  else
		   $month = strtotime('last month', $month);
		  $months[] = date("Y-m-d", $month);
		}
		$months=array_reverse($months);
	$jSon = "[['Year', 'Month']";
	for ($i=0;$i<count($months);$i++) 
	{
		$jSon.= ",['".date('M',strtotime($months[$i]))."-".date('Y',strtotime($months[$i]))."',".$this->getTotalUser(date('m',strtotime($months[$i])),date('Y',strtotime($months[$i])))."]";
	}
	$jSon.="]";
	//echo $jSon;
	?>
		<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable(<?=$jSon ?>);
			var options = {
			title: '',
			fontSize:'10',
				
			//hAxis: {title: 'Year', titleTextStyle: {color: 'red'}},
		};
		//var chart = new google.visualization.LineChart(document.getElementById('user-months'));
		var chart = new google.visualization.ColumnChart(document.getElementById('<?=$id?>'));
		chart.draw(data, options);
		}
		</script>
		<?php }
		public function userLocationChart($id){
			$query_user_location = "SELECT user.*,l.location,count(user.id) typecount from tbl_user user,tbl_location  l where l.id=user.user_location_id 
	GROUP BY user.user_location_id";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($query_user_location);
	$dataReader=$command->queryAll();
	$xaxis = "[['User Country', 'User Country']";
	foreach($dataReader as $row_user_location) 
	{
		$xaxis.=",['".$row_user_location['location']."', ".intval($row_user_location['typecount'])."]";
	}
	$xaxis.="]";
	?>
	<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable(<?=$xaxis?>);
	
			var options = {
			  title: '',
			 is3D: true,
			};
	
			var chart = new google.visualization.PieChart(document.getElementById('<?=$id?>'));
			chart.draw(data, options);
		  }
		</script>
	<?php }
}
