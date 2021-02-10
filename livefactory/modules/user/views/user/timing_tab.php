<?php
use livefactory\modules\pmt\controllers\TaskController;
use livefactory\models\TimeDiffModel;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
use livefactory\models\Defect;
use livefactory\models\Task;
use livefactory\models\Ticket;
?>
<?php
if(  (isset($assigned_user_id) && Yii::$app->user->identity->id == $assigned_user_id )|| Yii::$app->params['user_role'] == 'admin'){
	if(isset($_COOKIE['start_time'])){
		if($_COOKIE['taskStartedId']==$_GET['id']){
	$timeBtn='<a href="javascript:void(0)" class="btn btn-sm   btn-danger stopTime"  data-toggle="modal" ><i class="glyphicon glyphicon-time"></i> End Timer</a>'; 
		}
	}else{
		$timeBtn='<a href="index.php?r=pmt/task/task-view&id='.$_GET['id'].'&starttime=true" class="btn btn-sm  btn-success "><i class="glyphicon glyphicon-time"></i> Start Timer</a>';}
}?>
    <?php 
function approved($val){
	if($val=='0')
	$label = "<span class=\"label label-warning\">Waiting for Approval</span>";
	else if($val=='1')
	$label = "<span class=\"label label-success\">Approved</span>";	
	else
	$label = "<span class=\"label label-danger\">Rejected</span>";	
	return $label;
}
/*function getTimeDiff($to,$from){
	//echo $to;
	if($to !='-0001/11/30 00:00:00'){
	$datetime1 = new DateTime($to);
	$datetime2 = new DateTime($from);
	$interval = $datetime1->diff($datetime2);
	$elapsed = $interval->format('%H.%I');
	if($second)
	$elapsed = $interval->format('%H.%I.%s');
	return $elapsed;
	}else{
		return '00.00';	
	}
}*/
//echo Yii::$app->user->identity->id;
Yii::$app->request->enableCsrfValidation = true;
    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');
	$spend_t=0;
	$_REQUEST['solidNumTot']=0;
	$_REQUEST['dotNumTot']=0;
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModelTaskTime,
		
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

  //          'id',
            //'task_id',
            //'task_name',
			[
				'attribute' => 'Entry For',
					'width' => '10%' ,
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget) {
						if(isset($model->added_at))
						{
							if($model->entity_type == 'defect' && in_array('pmt',yii::$app->params['modules']))
							{
								return '<a href="index.php?r=pmt/defect/defect-view&id=' . $model->entity_id . '">' . Defect::findOne($model->entity_id)->defect_id . '</a>';
							}

							if($model->entity_type == 'task' && in_array('pmt',yii::$app->params['modules']))
							{
								return '<a href="index.php?r=pmt/task/task-view&id=' . $model->entity_id . '">' . Task::findOne($model->entity_id)->task_id . '</a>';
							}

							if($model->entity_type == 'ticket' && in_array('support',yii::$app->params['modules']))
							{
								return '<a href="index.php?r=suppport/ticket/update&id=' . $model->entity_id . '">' . Ticket::findOne($model->entity_id)->ticket_id . '</a>';
							}
						}
					} 
			],
			[ 
					'attribute' => 'start_time',
					'width' => '15%' ,
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->added_at)) 
						return date('Y-m-d H:i:s',$model->start_time);;
					} 
			],
			[ 
					'attribute' => 'end_time',
					'width' => '15%'  ,
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->added_at)) 
						return date('Y-m-d H:i:s',$model->end_time);
					} 
			],
			[ 
					'attribute' => 'notes',
					'format' => 'raw',
					'width' => '25%' 
			],
			[ 
					'attribute' => 'entry_type',
					'label'=>Yii::t('app','Entry Type'),
					'width' => '10%' 
			],
			[ 
					'attribute' => 'approved',
					'label'=>Yii::t('app','Status'),
					'width' => '10%' ,
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget) {
						return approved($model->approved);
					} 
			],
			
			
			 
			[ 
				'attribute' => 'user_id',
				'label' => Yii::t('app','User'),
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',
				'width' => '10%',
				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),
				'filterWidgetOptions' => [ 
						'options' => [ 
								'placeholder' => 'All...' 
						],
						'pluginOptions' => [ 
								'allowClear' => true 
						] 
				],
				'value' => function ($model, $key, $index, $widget) {
					//var_dump($model->user);
				if(isset($model->user) && !empty($model->user->first_name)) 
					return $model->user->username;
				} 
		],
			[ 
					'attribute' => 'start_time',
					'label'=>Yii::t('app','Time'),
					'width' => '10%' ,
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget) {
						$hours=$minutes=0;
						$timing=explode(',',TimeDiffModel::dateDiff($model->start_time,$model->end_time));
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
							if(strpos($value,'seconds') !== false){
								$second=trim(str_replace('seconds','',$value));
							}
							if(strpos($value,'second') !== false){
								$second=trim(str_replace('second','',$value));
							}
						}
						
						if(isset($day))
						$hours = ($day*24)+$hours;
						$_REQUEST['solidNumTot']+=$hours;
						$_REQUEST['dotNumTot']+=$minutes;
						if(isset($_REQUEST['secondTot']))
						$_REQUEST['secondTot']+=$second;
						return TimeDiffModel::dateDiff($model->start_time,$model->end_time);
						
					} 
			],
			],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Timesheets').' </h3>',
            'type'=>'info',
            'before'=>'<a href="index.php?r='.$_GET['r'].'&id='.$_GET['id'].'&approved=true" class="btn btn-primary btn-sm">Approved</a> <a href="index.php?r='.$_GET['r'].'&id='.$_GET['id'].'&pending=true" class="btn btn-warning btn-sm">Pending</a> <a href="index.php?r='.$_GET['r'].'&id='.$_GET['id'].'&rejected=true" class="btn btn-danger btn-sm">Rejected</a> '.'<a href="index.php?r='.$_GET['r'].'&id='.$_GET['id'].'" class="btn btn-success btn-sm">All</a> ',
			'after'=>'<div class="total_timing" align="right">ddd</div>',
            'showFooter'=>false
        ],
    ]); Pjax::end(); 
	///Seconds
	$plusNum1 = 0;
	$seconddotVal = 0;
	if(isset($_REQUEST['secondTot']))
	{
	list($plusNum1)=explode('.',$_REQUEST['secondTot']/60);
	$seconddotVal=round($_REQUEST['secondTot']%60);
	}
	$_REQUEST['dotNumTot'] =$_REQUEST['dotNumTot']+$plusNum1;
	$seconddotVal=strlen($seconddotVal)==1?$seconddotVal:$seconddotVal;
	
	list($plusNum)=explode('.',$_REQUEST['dotNumTot']/60);;
        $dotVal=round($_REQUEST['dotNumTot']%60);
        $solidNum =$_REQUEST['solidNumTot']+$plusNum;
        $spend_t=$solidNum." hours, ".$dotVal." minutes, ".$seconddotVal." seconds";
	?>
	<script>
		function delFunction(frm){
			var r = confirm("Are you sure!");
			if (r == true) {
				$('#'+frm).submit();
			} else {
				
			}
		}
		$(document).ready(function(e) {
			
            $('.total_timing').html('<strong><?=$spend_t?></strong>');
        });
		
	</script>