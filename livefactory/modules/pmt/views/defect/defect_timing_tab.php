<?php
use livefactory\modules\pmt\controllers\DefectController;
use livefactory\models\TimeDiffModel;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
?>
<?php
$_REQUEST['dd']=100;
if(Yii::$app->user->identity->id == $assigned_user_id || Yii::$app->params['user_role'] =='admin'){
	if(isset($_COOKIE['defect_start_time'])){
		if($_COOKIE['defectStartedId']==$_GET['id']){
	$timeBtn='<a href="javascript:void(0)" class="btn btn-sm   btn-danger stopTime"  data-toggle="modal" ><i class="glyphicon glyphicon-time"></i> End Timer</a>'; 
		}
	}else{
		$timeBtn='<a href="index.php?r=pmt/defect/defect-view&id='.$_GET['id'].'&starttime=true" class="btn btn-sm  btn-success "><i class="glyphicon glyphicon-time"></i> Start Timer</a>';}
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
        'dataProvider' => $dataProviderDefectTime,
        //'filterModel' => $searchModelDefectTime,
		
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

  //          'id',
            //'defect_id',
            //'defect_name',
			[ 
					'attribute' => 'start_time',
					'width' => '10%' ,
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->added_at)) 
						return date('jS \of M Y H:i:s',strtotime($model->start_time));
					} 
			],
			[ 
					'attribute' => 'end_time',
					'width' => '10%'  ,
					'value' => function ($model, $key, $index, $widget) {
					if(isset($model->added_at)) 
						return date('jS \of M Y H:i:s',strtotime($model->end_time));
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
							if(strpos($value,'hour') !== false){
								$hours=trim(str_replace('hour','',$value));
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
							if(strpos($value,'minute') !== false){
								$minutes=trim(str_replace('minutes','',$value));
							}
						}
						$hours = ($day*24)+$hours;
						$_REQUEST['solidNumTot']+=$hours;
						$_REQUEST['dotNumTot']+=$minutes;
						return TimeDiffModel::dateDiff($model->start_time,$model->end_time);
						
					} 
			],
		[ 
					'class' => '\kartik\grid\ActionColumn',
					'template'=>'{update} {approve}  {reject}  {delete} ',
					'buttons' => [ 
							'width' => '100px',
							'update' => function ($url, $model)
							{
								 return"<form name='frm_time".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&time_entry_id=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_time".$model->id.".submit()' title='Edit' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";
							},
							'approve' => function ($url, $model)
							{
								if(DefectController::getProjectOwnerId($_SESSION['project_id']) ==Yii::$app->user->identity->id){
								$approve = $model->approved=='1'?'No':'Yes';
								$approveBtn = $model->approved=='1'?'glyphicon glyphicon-thumbs-down':'glyphicon glyphicon-thumbs-up';
								 return"<form name='frm_time1".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&approved=".$approve."&appid=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_time1".$model->id.".submit()' title='Approve'  target='_parent'><span class='".$approveBtn."'></span></a></form>";
								}else{
									return '';	
								}
							},
							'reject' => function ($url, $model)
							{
								//$approve = $model->approved=='1'?'No':'Yes';
								//$approveBtn = $model->approved=='1'?'glyphicon glyphicon-thumbs-down':'glyphicon glyphicon-thumbs-up';
								if(DefectController::getProjectOwnerId($_SESSION['project_id']) ==Yii::$app->user->identity->id){
								 return"<form name='frm_time4".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&approved=reject&appid=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_time4".$model->id.".submit()' title='Rejected'  target='_parent'><span class='glyphicon glyphicon-eye-close'></span></a></form>";
									}else{
									return '';	
								}
							},
						'delete' => function($url,$model){
							 return"<form id='frm_time2".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&time_del_id=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick=\"delFunction('frm_time2".$model->id."')\" title='Delete' target='_parent'><span class='glyphicon glyphicon-trash'></span></a></form>";
						
						}
						
							 
					]
			],
			],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        //'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Defect Timing').' </h3>',
            'type'=>'info',
            'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm addTiming" onClick="$(\'.defect_timing\').modal(\'show\');"><i class="glyphicon glyphicon-plus"></i>'.Yii::t('app','Add Manual Entry').'</a> '.$timeBtn,             'after'=>'<div class="total_timing" align="right">ddd</div>',
            'showFooter'=>false
        ],
    ]); Pjax::end(); 
	list($plusNum)=explode('.',$_REQUEST['dotNumTot']/60);;
        $dotVal=round($_REQUEST['dotNumTot']%60);
        $solidNum =$_REQUEST['solidNumTot']+$plusNum;
        $spend_t=$solidNum." hours, ".$dotVal." minutes";
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