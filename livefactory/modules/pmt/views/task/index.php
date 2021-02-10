<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\TaskPriority;
use livefactory\models\TaskStatus;
use livefactory\models\Project;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Task $searchModel
 */

$this->title = Yii::t ( 'app', 'Tasks' );
$this->params ['breadcrumbs'] [] = $this->title;
date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
function statusLabel($status)
{
	if (in_array ( strtolower ( $status ), array (
			'new',
			'business',
			'completed',
			'low' 
	) ))
	{
		$label = "<span class=\"label label-primary\">" . $status . "</span>";
	}
	else if (in_array ( strtolower ( $status ), array (
			'acquired',
			'in process',
			'medium',
			'p2',
			'p3' 
	) ))
	{
		$label = "<span class=\"label label-success\">" . $status . "</span>";
	}
	else if (in_array ( strtolower ( $status ), array (
			'individual',
			'lowest' 
	) ))
	{
		$label = "<span class=\"label label-info\">" . $status . "</span>";
	}
	else if (in_array ( strtolower ( $status ), array (
			'lost',
			'needs action',
			'highest' 
	) ))
	{
		$label = "<span class=\"label label-danger\">" . $status . "</span>";
	}
	else if (in_array ( strtolower ( $status ), array (
			'student',
			'on hold',
			'high' 
	) ))
	{
		$label = "<span class=\"label label-warning\">" . $status . "</span>";
	}
	else
	{
		$label = "<span class=\"label label-default\">" . $status . "</span>";
	}
	return $label;
}
?>
<div class="task-index">

    <?php Yii::$app->request->enableCsrfValidation = true; ?>
    
    <?php
			if(Yii::$app->params['user_role']=='admin'){
					$projects =ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->asArray ()->all (), 'id', 'project_name' ) ;	
				}else{
					$projects=ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->where("EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray ()->all (), 'id', 'project_name' ) ;	
				}	
Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
						
						'pjax' => true,
						'columns' => [ 
								['class' => '\kartik\grid\CheckboxColumn'],
            					['class' => 'yii\grid\SerialColumn'],
								
								[ 
										'attribute' => 'task_id',
										//'width' => '350px' ,
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return '<a href="index.php?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>';
										}
								],
								[ 
										'attribute' => 'task_name',
										'width' => '350px' ,
										'format' => 'raw',
										
								],
								
								// 'task_description:ntext',
								// ['attribute'=>'actual_end_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'actual_start_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'time_spent',
								// 'assigned_user_id',
								[ 
										'attribute' => 'user_assigned_id',
										'label' => Yii::t('app','User'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->where("active=1")->asArray ()->all (), 'id',
										function ($user, $defaultValue) {
       								 $username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    }),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...') 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->user);
											if (isset ( $model->user ) && ! empty ( $model->user->first_name )){
												$username=$model->user->username?$model->user->username:$model->user->email;
						if(Yii::$app->params['USER_IMAGE'] =='Yes'){
							$users='<div class="project-people">';
									$path='../users/'.$model->user->id.'.png';
									if(file_exists($path)){
										$image='<img  class="img-circle"  src="'.$path.'"  data-toggle="hover" data-placement="top" data-content="'.$model->user->first_name.' '.$model->user->last_name.' ('.$model->user->username.')">';								
									 }else{ 
										$image='<img   class="img-circle" src="../users/nophoto.jpg"  data-toggle="hover" data-placement="top" data-content="'.$model->user->first_name.' '.$model->user->last_name.' ('.$model->user->username.')">';
									 }
									$users.=' <a  href="javascript:void(0)" onClick="showPopup(\''.$model->user->id.'\')">'.$image.'</a>';	
						
								$users.='</div>';
								return $users;
							}else{
								$users=' <a  href="javascript:void(0)" onClick="showPopup(\''.$model->user->id.'\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">'.$model->user->first_name.' '.$model->user->last_name.' ('.$username.')</a>';
								return $users;
							}
											}
										} 
								],
								
								// 'payment_rate',
								// 'project_id',
								// ['attribute'=>'expected_start_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'expected_end_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'task_status_id',
								// 'task_priority_id',

								[ 
										'attribute' => 'expected_end_datetime',
										'label'=> Yii::t('app','Due'),
										'filterType' => GridView::FILTER_DATE,
										'width' => '200px',
										'filterWidgetOptions' => [ 
'language' => 'eg',
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
										],
										'value' => function ($model, $key, $index, $widget) {
										if(isset($model->expected_end_datetime)) 
											return date('F d, Y',($model->expected_end_datetime));
										} 
								],
								/*
								[ 
										'attribute' => 'actual_end_datetime',
										'label'=>'Completed',
										'filterType' => GridView::FILTER_DATETIME,
										'width' => '170px',
										'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd H:i:s' 
												] 
										]
								],
								*/
								[ 
										'attribute' => 'project_id',
										'label' => Yii::t('app','Project'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										//'width' => '120px',
										'filter' => $projects,
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...')  
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->taskPriority);
											if (isset ( $model->project ) && ! empty ( $model->project->project_name ))
												return '<a href="index.php?r=pmt/project/project-view&id='.$model->project->id.'">'.$model->project->project_name.'</a>';
										} 
								],
								[ 
										'attribute' => 'task_status_id',
										'label' => Yii::t('app','Status'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										//'width' => '100px',
										'filter' => ArrayHelper::map ( TaskStatus::find ()->where("active=1")->orderBy ('sort_order' )->asArray ()->all (), 'id', 'label' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...') 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->taskPriority);
											if (isset ( $model->taskStatus ) && ! empty ( $model->taskStatus->label ))
												return statusLabel ( $model->taskStatus->label );
										} 
								],
								[ 
										'attribute' => 'task_priority_id',
										'label' => Yii::t('app','Priority'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										//'width' => '100px',
										'filter' => ArrayHelper::map ( TaskPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...') 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->taskPriority);
											if (isset ( $model->taskPriority ) && ! empty ( $model->taskPriority->label ))
												return statusLabel ( $model->taskPriority->label );
										} 
								],
								
								// ['attribute'=>'date_added','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'date_modified','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'task_progress',
								[ 
										'attribute' => 'task_progress',
										//'width'=>'80px',
										'label' => Yii::t('app','Progress'),
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
$per = $model->task_progress==''?0:$model->task_progress;
											return '<small>Progress: '.$per.'%</small>
<div class="progress progress-mini">
<div class="progress-bar" style="width:'.$model->task_progress.'%;"></div>';
											/*return "<div class='progress'>
  <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='" . $model->task_progress . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $model->task_progress . "%'>" . $model->task_progress . "</div>
</div>";*/
										} 
								],
								// 'parent_id',
								// 'added_at',
								// 'updated_at',
								
								[ 
										'class' => '\kartik\grid\ActionColumn',
										'header'=>'Actions',
										'template'=>'{update} {view} {delete}',
										'buttons' => [ 
												'width' => '100px',
												'update' => function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/task/task-view',
															'id' => $model->id 
													] ), [ 
															'title' => Yii::t('app', 'Edit' ) 
													] );
												},
											'view' => function($url,$model){
												return '';
											
											},
											'delete' => function($url,$model){
															return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['pmt/task/delete','id' => $model->id]), [
														'title' => Yii::t('app', 'Delete'),
														'data' => [                          
																	'method' => 'post',                          
																	'confirm' => Yii::t('app', 'Are you sure?')],
																  ]);
														}
												 
										]
										 
								] 
						],
						'responsive' => true,
						'hover' => true,
						'condensed' => true,
						'floatHeader' => false,
						
						'panel' => [ 
								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app', Html::encode ( $this->title )) . ' </h3>',
								'type' => 'info',
								'before' => '<form action="" method="post" name="frm"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_del" value="true">'.Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
                'create'
            ], [
                'class' => 'btn btn-success btn-sm'
            ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', "Delete Selected") . '</a>',
            'after' => '</form>'.Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                'index'
            ], [
                'class' => 'btn btn-info btn-sm'
            ]),
            'showFooter' => false
						] 
				] );
				Pjax::end ();
				?>
<!-- </form> -->
<script>
	function all_del(){
		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");
		if (r == true) {
			document.frm.submit()
		} else {
			
		}	
	}
</script>
<script>
	function showPopup(id){
		//alert('index.php?r=liveobjects/queue/ajax-user-detail&id='+id);
		$.post('index.php?r=liveobjects/queue/ajax-user-detail&id='+id,function(r){
			$('.modal-body').html(r);
		}).done(function(){
			$('.bs-example-modal-lg').modal('show');
		})
	}
</script>
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
    </div>
      <div class="modal-body">
      
      </div>
    </div>
  </div>
</div>
