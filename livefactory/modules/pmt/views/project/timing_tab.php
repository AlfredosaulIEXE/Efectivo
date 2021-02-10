<?php

use livefactory\modules\pmt\controllers\TaskController;

use livefactory\models\TimeDiffModel;

use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;

use yii\helpers\ArrayHelper;
function getName($id){
$sql = "SELECT * from tbl_task where id=$id";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$dataReader=$command->queryOne();	
			return '<a href="index.php?r=pmt/task/task-view&id='.$dataReader['id'].'" >'.$dataReader['task_name'].'</a>';
}
?>

    <?php 

function approved($val){

	if($val=='0')

	$label = "<span class=\"label label-warning\">Waiting for Approval</span>";

	else if($val=='1')

	$label = "<span class=\"label label-primary\">Approved</span>";	

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

        'dataProvider' => $dataProviderTime,

        //'filterModel' => $searchModelTaskTime,

		

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



  //          'id',

            //'task_id',

            //'task_name',

			[ 

					'attribute' => 'entity_id',
					'label'=>Yii::t('app','Task Title'),

					'width' => '10%' ,
					'format' => 'raw',

					'value' => function ($model, $key, $index, $widget) {

						return getName($model->entity_id);

					} 

			],
			[ 

					'attribute' => 'start_time',

					//'width' => '10%' ,

					'value' => function ($model, $key, $index, $widget) {

					if(isset($model->added_at)) 

						return date('jS \of M Y H:i:s',strtotime($model->start_time));

					} 

			],

			[ 

					'attribute' => 'end_time',

					//'width' => '10%'  ,

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

				//	'width' => '10%' 

			],

			[ 

					'attribute' => 'approved',

					'label'=>Yii::t('app','Status'),

					//'width' => '10%' ,

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

				//'width' => '10%',

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

					//'width' => '10%' ,

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

						$hours = ($day*24)+$hours;

						$_REQUEST['solidNumTot']+=$hours;

						$_REQUEST['dotNumTot']+=$minutes;
						$_REQUEST['secondTot']+=$second;
						return TimeDiffModel::dateDiff($model->start_time,$model->end_time);

						

					} 

			],

		[ 

					'class' => '\kartik\grid\ActionColumn',

					'template'=>'{update} {approve}  {reject}  {delete} ',
					'contentOptions' => ['style' => 'width:150px;'],
					'header'=>'Action',

					'buttons' => [ 

							'width' => '100px',

							'update' => function ($url, $model)

							{

								 return '';

							},

							'approve' => function ($url, $model)

							{
								$approve = $model->approved=='1'?'No':'Yes';

								$approveBtn = $model->approved=='1'?'glyphicon glyphicon-thumbs-down':'glyphicon glyphicon-thumbs-up';
								$approveClass = $model->approved=='1'?'btn-danger':'btn-primary';
								if($model->approved !='1'){
								 return"<form name='frm_time1".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&approved=".$approve."&appid=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>

									<a href='#' onClick='document.frm_time1".$model->id.".submit()' title='Approve' class='btn ".$approveClass." btn-xs'  target='_parent'> Approve </a></form>";
								}else{
									return '';	
								}

							},

							'reject' => function ($url, $model)

							{
								if($model->approved !='-1'){
									 return"<form name='frm_time4".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&approved=reject&appid=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>

									<a href='#' onClick='document.frm_time4".$model->id.".submit()' title='Rejected' class='btn btn-danger btn-xs'  target='_parent'>Reject</a></form>";
								}else return '';

							},

						'delete' => function($url,$model){

							 return '';

						

						}

						

							 

					]

			],

			],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app','Approve Timing').' </h3>',

            'type'=>'info',

            'before'=>'<a href="index.php?r=pmt/project/project-view&id='.$_GET['id'].'&approved=true" class="btn btn-primary btn-sm">Approved</a> <a href="index.php?r=pmt/project/project-view&id='.$_GET['id'].'&pending=true" class="btn btn-warning btn-sm">Pending</a> <a href="index.php?r=pmt/project/project-view&id='.$_GET['id'].'&rejected=true" class="btn btn-danger btn-sm">Rejected</a> '.'<a href="index.php?r=pmt/project/project-view&id='.$_GET['id'].'" class="btn btn-success btn-sm">All</a>',
			'after'=>'<div class="total_timing" align="right">ddd</div>',
            'showFooter'=>false

        ],

    ]); Pjax::end(); 
	///Seconds 
	list($plusNum1)=explode('.',$_REQUEST['secondTot']/60);
	$seconddotVal=round($_REQUEST['secondTot']%60);
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

			

            $('.total_timing').html('<strong>Total Time: <?=$spend_t?></strong>');

        });

		

	</script>