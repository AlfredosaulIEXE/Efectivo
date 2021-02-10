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

function statusLabel2($status)
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

			//			'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,

						'columns' => [ 

								//['class' => '\kartik\grid\CheckboxColumn'],

            					['class' => 'yii\grid\SerialColumn'],

								[ 

										'attribute' => 'task_id',

										'format' => 'raw',

										'value' => function ($model, $key, $index, $widget)

										{

											return '<a href="index.php?r=pmt/task/task-view&id='.$model->id.'">'.$model->task_id.'</a>';

										}

								],
								
								[ 

										'attribute' => 'task_name',

										'width' => '250px' ,

										'format' => 'raw',

										

								],

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
												return $model->user->first_name.' '.$model->user->last_name.' ('.$username.')';
											}

										} 

								],



								[ 

										'attribute' => 'expected_end_datetime',

										'label'=> Yii::t('app','Due'),

										'filterType' => GridView::FILTER_DATETIME,

										'width' => '170px',

										'filterWidgetOptions' => [ 
'language' => 'eg',
												'pluginOptions' => [ 

														'format' => 'yyyy-mm-dd H:i:s' 

												] 

										],

										'value' => function ($model, $key, $index, $widget) {

										if(isset($model->expected_end_datetime)) 

											return date('Y-m-d H:i:s',$model->expected_end_datetime);

										} 

								],

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

												return $model->project->project_name;

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

												return statusLabel2 ( $model->taskStatus->label );

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

												return statusLabel2 ( $model->taskPriority->label );

										} 

								],

								[ 

										'attribute' => 'task_progress',

										//'width'=>'80px',

										'label' => Yii::t('app','Progress'),

										'format' => 'raw',

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

								

								

						],

						'responsive' => true,

						'hover' => true,

						'condensed' => true,

						'floatHeader' => false,

						

						'panel' => [ 

								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app','Tasks') . ' </h3>',

								'type' => 'info',

								'before' =>'',

								'after' => '',

								'showFooter' => false 

						] 

				] );

				Pjax::end ();

				?>

</div>

