<?php

namespace livefactory\models;
use livefactory\models\AssignmentHistory;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\Lead as LeadModel;
use livefactory\models\search\CommonModel;
class LeadReport extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
	public function getTotalLeadType($month,$year,$type){
		$sql = "SELECT count(tbl_lead.id) tot, tbl_lead_type.label label FROM tbl_lead,tbl_lead_type where tbl_lead.lead_type_id=tbl_lead_type.id and from_unixtime(tbl_lead.added_at, '%m') ='$month' and from_unixtime(tbl_lead.added_at, '%Y') ='$year' and  tbl_lead_type.label='$type'   group by  tbl_lead_type.label ";
		$connection = \Yii::$app->db;
		$command=$connection->createCommand($sql);
		$row=$command->queryOne();
		return $row['tot']?$row['tot']:0;
	} 
    public function getNewLeadWithTypeChat($id){
	$month = time();
	for ($i = 1; $i <= 12; $i++) {
		if($i==1)
	  $month = strtotime(date('M', $month), $month);
	  else
	   $month = strtotime('last month', $month);
	  $months[] = date("Y-m-d", $month);
	}
	$months=array_reverse($months);
	$sql = "SELECT count(tbl_lead.id) tot, tbl_lead_type.label label FROM tbl_lead,tbl_lead_type where tbl_lead.lead_type_id=tbl_lead_type.id group by  tbl_lead_type.label ";
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	$jSon = "[['Year',";
	$cm='';
	$typeArray = array();
	foreach($dataReader as $cr){
		$typeArray[]=$cr['label'];
		$jSon .=$cm. "'".$cr['label']."'";
		$cm=',';
	}
	$jSon .= "]";
	for ($i=0;$i<count($months);$i++) 
	{
		$jSon.= ",['".date('M',strtotime($months[$i]))."-".date('Y',strtotime($months[$i]))."',";
		$c='';
		foreach ($typeArray as $tp) 
		{
			$jSon.= $c.$this->getTotalLeadType(date('m',strtotime($months[$i])),date('Y',strtotime($months[$i])),$tp);
			$c=',';
		}
		$jSon .= "]";
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
        legend: { position: 'top', maxLines: 3 },
        bar: { groupWidth: '75%' },
        isStacked: true,
		fontSize:'10',
		colors: ['#ed5565', '#1c84c6', '#1ab394', '#d1dade'],
      };
	//var chart = new google.visualization.LineChart(document.getElementById('lead-months'));
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$id?>'));
	chart.draw(data, options);
	}
    </script>
    <?php }
	 public function getTotalLeads($month,$year){
	$sql = "SELECT * FROM tbl_lead WHERE  from_unixtime(added_at, '%m') ='$month' and from_unixtime(added_at, '%Y') ='$year' ";
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	return count($dataReader);
}
	public function newLeadChart($id){
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
		$jSon.= ",['".date('M',strtotime($months[$i]))."-".date('Y',strtotime($months[$i]))."',".$this->getTotalLeads(date('m',strtotime($months[$i])),date('Y',strtotime($months[$i])))."]";
	}
	$jSon.="]";
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
	//var chart = new google.visualization.LineChart(document.getElementById('lead-months'));
	var chart = new google.visualization.ColumnChart(document.getElementById('<?=$id?>'));
	chart.draw(data, options);
	}
    </script>
   <?php }
   public function leadTypeChart($id){
	   $sql = "SELECT label.label lead_type,count(cus.id) typecount from tbl_lead cus,tbl_lead_type label where label.id=cus.lead_type_id 
	GROUP BY lead_type";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	$ctype = "[['Lead Type', 'Lead Type']";
	foreach($dataReader as $row_lead_type) 
	{
		$ctype.=",['".$row_lead_type['lead_type']."', ".intval($row_lead_type['typecount'])."]";
	}
	$ctype.="]";
	?>
	<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable(<?=$ctype?>);
	
			var options = {
			  title: '',
			  is3D: true,
			// pieHole: 0.3,
			};
	
			var chart = new google.visualization.PieChart(document.getElementById('<?=$id?>'));
			chart.draw(data, options);
		  }
		</script>
	<?php
	}

	public function salesFunnelChart($id){
	  $sql = "select b.status status, count(lead_status_id) count from tbl_lead a, tbl_lead_status b where a.lead_status_id = b.id
				group by lead_status_id";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();

	$totalLeads=0;
	$totalInteractions=0;
	$totalOpportunities=0;
	$totalConverted=0;

	foreach($dataReader as $row) 
	{
		if(!strcmp($row['status'], "New"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "In Process"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Opportunity"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
			$totalOpportunities = $totalOpportunities+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Converted"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
			$totalOpportunities = $totalOpportunities+intval($row['count']);
			$totalConverted = $totalConverted+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Recycled"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Dead"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
		}
	}
	?>
	 <script type="text/javascript" src="../../vendor/bower/jquery/dist/jquery.js"></script>
	<script type="text/javascript" src="../include/jqplot/jquery.jqplot.js"></script>
	<script type="text/javascript" src="../include/jqplot/plugins/jqplot.funnelRenderer.js"></script>
	<link rel="stylesheet" type="text/css" href="../include/jqplot/jquery.jqplot.css" />
	<script type="text/javascript">
		var data = [['Inflow', <?=$totalLeads?>], ['Interactions (<?=round(($totalInteractions/$totalLeads)*100)?>%)', <?=$totalInteractions?>], ['Opportunities (<?=round(($totalOpportunities/$totalLeads)*100)?>%)', <?=$totalOpportunities?>], ['Converted (<?=round(($totalConverted/$totalLeads)*100)?>%)', <?=$totalConverted?>]];
		var options = {
			seriesDefaults: {
				renderer: $.jqplot.FunnelRenderer,
					rendererOptions: {
					dataLabels: ['value'],
					showDataLabels: true,
					sectionMargin: 5,
					widthRatio: 0.0
				}
			},

			/*title: {
				text: 'Sales Funnel'
			},*/

			legend: {
					location: 'e',
					show: true,
					placement: "inside",
					/*marginTop : "0%",
					marginBottom : "40%",
					marginRight : "50%",
					marginLeft : "10%",*/
					fontSize: '10pt'
			}
		};

		$.jqplot('<?=$id?>', [data], options);
	</script>

	<?php
	}

	public function salesFunnelChart2($id, $start, $end, $office_id = null){
        $office = CommonModel::getOfficeSql($office_id, 'a');
        $thisMonthFirstDate = strtotime($start);
        $thisMonthLastDate = strtotime($end);

	  $sql = "select b.status status, count(lead_status_id) count from tbl_lead a, tbl_lead_status b where a.lead_status_id = b.id and (a.added_at) >='$thisMonthFirstDate' and (a.added_at) <='$thisMonthLastDate' $office
				group by status";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();

	$totalLeads=0;
	$totalInteractions=0;
	$totalOpportunities=0;
	$totalConverted=0;

	foreach($dataReader as $row) 
	{
		if(!strcmp($row['status'], "New"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "In Process"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Opportunity"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
			$totalOpportunities = $totalOpportunities+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Converted"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
			$totalOpportunities = $totalOpportunities+intval($row['count']);
			$totalConverted = $totalConverted+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Recycled"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
			$totalInteractions = $totalInteractions+intval($row['count']);
		}
		else
		if(!strcmp($row['status'], "Dead"))
		{
			$totalLeads = $totalLeads+intval($row['count']);
		}
	}
	?>
	<script type="text/javascript" src="../include/d3.js"></script>
	<script type="text/javascript" src="../include/d3-funnel.js"></script>
	<script type="text/javascript">
		const data = [['<?=Yii::t('app', 'Leads')?>', <?=$totalLeads?>,'#23c6c8'], ['<?=Yii::t('app', 'Appointments')?> (<?=round(($totalInteractions/$totalLeads)*100)?>%)', <?=$totalInteractions?>,'#f8ac59'], ['<?=Yii::t('app', 'UPS')?> (<?=round(($totalOpportunities/$totalLeads)*100)?>%)', <?=$totalOpportunities?>,'#a7aeb2'], ['<?=Yii::t('app', 'Sales')?> (<?=round(($totalConverted/$totalLeads)*100)?>%)', <?=$totalConverted?>,'#1ab394']];
		const options = {
        block: {
            dynamicHeight: true,
            minHeight: 15,
			highlight: true,
		},
		chart: {
				curve: {
						enabled: true,
						height: 50,
				},
				bottomWidth: 1/4,
				bottomPinch: 1,
			},
		};
		const chart = new D3Funnel('#<?=$id?>');
		//chart.curve.enabled = true;
		chart.draw(data, options);
	</script>

	<?php
	}

    public function salesFunnelChart3($totalLeads=0, $totalInteractions=0, $totalOpportunities=0, $totalConverted=0){

        $id = 'sales-funnel';
        ?>
        <script type="text/javascript" src="../include/d3.js"></script>
        <script type="text/javascript" src="../include/d3-funnel.js"></script>
        <script type="text/javascript">
            const data = [['<?=Yii::t('app', 'Leads')?>', <?=$totalLeads?>,'#23c6c8'], ['<?=Yii::t('app', 'Appointments')?> (<?=round(($totalInteractions/$totalLeads)*100)?>%)', <?=$totalInteractions?>,'#f8ac59'], ['<?=Yii::t('app', 'UPS')?> (<?=round(($totalOpportunities/$totalLeads)*100)?>%)', <?=$totalOpportunities?>,'#a7aeb2'], ['<?=Yii::t('app', 'Sales')?> (<?=round(($totalConverted/$totalLeads)*100)?>%)', <?=$totalConverted?>,'#1ab394']];
            const options = {
                block: {
                    dynamicHeight: true,
                    minHeight: 15,
                    highlight: true,
                },
                chart: {
                    curve: {
                        enabled: true,
                        height: 50,
                    },
                    bottomWidth: 1/4,
                    bottomPinch: 1,
                },
            };
            const chart = new D3Funnel('#<?=$id?>');
            //chart.curve.enabled = true;
            chart.draw(data, options);
        </script>

        <?php
    }

	public function leadStatusChart($id){
	   $sql = "SELECT label.label lead_status,count(cus.id) statuscount from tbl_lead cus,tbl_lead_status label where label.id=cus.lead_status_id 
	GROUP BY lead_status";
	
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
	$cstatus = "[['Lead Status', 'Lead Status']";
	foreach($dataReader as $row_lead_status) 
	{
		$cstatus.=",['".$row_lead_status['lead_status']."', ".intval($row_lead_status['statuscount'])."]";
	}
	$cstatus.="]";
	?>
	<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable(<?=$cstatus?>);
	
			var options = {
			  title: '',
			  is3D: true,
			// pieHole: 0.3,
			};
	
			var chart = new google.visualization.PieChart(document.getElementById('<?=$id?>'));
			chart.draw(data, options);
		  }
		</script>
	<?php
	}


	public function leadCountryChart($id){
	$sql = "SELECT cou.country,count(cus.id) typecount from tbl_lead cus,tbl_address a, tbl_country cou where a.entity_id=cus.id and a.entity_type='lead' and a.is_primary='1' and a.country_id=cou.id
GROUP BY cou.country";
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$dataReader=$command->queryAll();
$xaxis = "[['Lead Country', 'Lead Country']";
foreach($dataReader as $row_lead_country) 
{
	$xaxis.=",['".$row_lead_country['country']."', ".intval($row_lead_country['typecount'])."]";
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
 <?php	
}
}
