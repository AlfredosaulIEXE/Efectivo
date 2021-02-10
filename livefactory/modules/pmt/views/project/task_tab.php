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


    <?php
	Yii::$app->request->enableCsrfValidation = true;
    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');			
Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProviderTask,
						//'filterModel' => $searchModelTask,
						'pjax' => true,
						'columns' => [ 
								[ 
										'class' => 'yii\grid\SerialColumn' 
								],
								
								[ 
										'attribute' => 'task_id',
										'label'=>'Task ID',
										'width' => '100px',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return '<a href="index.php?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>';
										} 
								],
								[ 
										'attribute' => 'task_name',
										'label'=>'Task Title',
										'width' => '500px',
										'format' => 'raw',
										'value' => $model->task_name
								],
								[ 
										'attribute' => 'parent_id',
										'label'=> Yii::t('app', 'Parent Task'),
										'width' => '150px',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											if(isset($model->taskParent) && !empty($model->taskParent->task_name)){
											return '<a href="index.php?r=pmt/task/task-view&id='.$model->taskParent->id.'">'.$model->taskParent->task_name.'</a>';
											}
										} 
								],
								[ 
										'attribute' => 'user_assigned_id',
										'label' => Yii::t('app','User'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'first_name' ),
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
												return $model->user->first_name.' '.$model->user->last_name.' ('.$username.')';
											}
										} 
								],
								[ 
										'attribute' => 'actual_start_datetime',
										'label' => Yii::t('app', 'Start'),
										'format' => 'raw',
										'width' => '150px' ,
										'value' => function ($model, $key, $index, $widget) {
										if(isset($model->actual_start_datetime)) {
											if($model->actual_start_datetime==''){
												return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
											}else{
											return date('jS \of M Y H:i:s',($model->actual_start_datetime));
												}
											}
										} 
								],
								[ 
										'attribute' => 'actual_end_datetime',
										'label' => Yii::t('app', 'Completed'),
										'format' => 'raw',
										'width' => '150px' ,
										'value' => function ($model, $key, $index, $widget) {
										if(isset($model->actual_end_datetime)) {
											if($model->actual_end_datetime==''){
												return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
											}else{
											return date('jS \of M Y H:i:s',($model->actual_end_datetime));
												}
											}
										} 
								],
								
								[ 
										'attribute' => 'task_status_id',
										'label' => Yii::t('app', 'Status'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( TaskStatus::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
										'width' => '100px',
										'filter' => ArrayHelper::map ( TaskPriority::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
								// 'progress',
								[ 
										'attribute' => 'task_progress',
										'label' =>Yii::t('app', 'Progress') ,
										'format' => 'raw',
										'width' => '100px',
										'value' => function ($model, $key, $index, $widget)
										{
											return "<div class='progress'>
  <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='" . $model->task_progress . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $model->task_progress . "%'>" . $model->task_progress . "</div>
</div>";
										} 
								],
								
								// 'parent_id',
								// 'added_at',
								// 'updated_at',
								
								[ 
										'class' => '\kartik\grid\ActionColumn',
										'template'=>'{update} {view} {delete}',
										'buttons' => [ 
												'width' => '100px',
												'update' => function ($url, $model)
												{
													/*return"<form name='frm_task".$model->id."' action='index.php?r=pmt/task/task-view&id=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_task".$model->id.".submit()' title='View' target='_parent'><span class='glyphicon glyphicon-eye-open'></span></a></form>";*/
									return '<a href="index.php?r=pmt/task/task-view&id='.$model->id.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
													/*return Html::a ( '<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/task/task-view',
															'id' => $model->id 
													] ), [ 
															'title' => Yii::t('app', 'Edit' ) 
													] );*/
												} ,
											'delete' => function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/project/project-view',
															'task_del' => $model->id,
															'id' => $_GET['id'] 
													] ), [ 
															'title' => Yii::t('app', 'Delete' ) ,
															'onClick'=>"return confirm('Are You Sure!')"
													] );
												} ,
											'view' => function($url,$model){
												return '';
											
											}
										]
										 
								] 
						],
						'responsive' => true,
						'hover' => true,
						'condensed' => true,
						'floatHeader' => false,
						
						'panel' => [ 
								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Tasks').' </h3>',
								'type' => 'info',
								'showFooter' => false ,
								'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.taskae\').modal(\'show\');"><i class="fa fa-edit"></i> '.Yii::t('app', 'New Task').'</a>'
						] 
				] );
				Pjax::end ();
				?>
