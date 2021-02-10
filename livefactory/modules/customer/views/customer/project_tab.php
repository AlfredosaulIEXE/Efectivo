<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\ProjectStatus;
use livefactory\models\Customer;
use livefactory\models\ProjectType;
use livefactory\models\Project;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Project $searchModel
 */


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
<div class="project-index">
    <?php
				
Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProvider,
						'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
						'columns' => [ 
								[ 
										'class' => 'yii\grid\SerialColumn' 
								],
								
								// 'id',
								// 'parent_project_id',
								
								// 'project_name',
								
								[ 
										'attribute' => 'project_name',
										'width' => '200px',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return '<a href="?r=pmt/project/project-view&id=' . $model->id . '">' . $model->project_name . '</a>';
										} 
								],
								
								
								// 'added_by',
								/*
								 * [
								 * 'attribute' => 'added_by',
								 * 'label' => 'Added by',
								 * 'filterType' => GridView::FILTER_SELECT2,
								 * 'format' => 'raw',
								 * 'width' => '120px',
								 * 'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'first_name' ),
								 * 'filterWidgetOptions' => [
								 * 'options' => [
								 * 'placeholder' => 'All...'
								 * ],
								 * 'pluginOptions' => [
								 * 'allowClear' => true
								 * ]
								 * ],
								 * 'value' => function ($model, $key, $index, $widget)
								 * {
								 * // var_dump($model->user);
								 * if (isset ( $model->user ) && ! empty ( $model->user->first_name ))
								 * return $model->user->first_name." ".$model->user->last_name;
								 * }
								 * ],
								 */
								// 'project_type_id',
								[ 
										'attribute' => 'project_type_id',
										//'label' => 'Project Type',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
									//	'width' => '250px',
										'filter' => ArrayHelper::map ( ProjectType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
											if (isset ( $model->type ) && ! empty ( $model->type->label ))
												return $model->type->label;
										} 
								],
								[ 
										'attribute' => 'project_status_id',
										//'label' => 'Status',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
									//	'width' => '250px',
										'filter' => ArrayHelper::map ( ProjectStatus::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
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
											if (isset ( $model->status ) && ! empty ( $model->status->label ))
												return statusLabel ( $model->status->label );
										} 
								],
								
								// 'last_updated_by',
								// 'project_owner',
								
								/*
								[ 
										'attribute' => 'project_owner',
										'label' => 'Owner',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'first_name' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => 'All...' 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->user);
											if (isset ( $model->user1 ) && ! empty ( $model->user1->first_name ))
												return $model->user1->first_name . " " . $model->user1->last_name;
										} 
								],
								
								*/
								
								// 'added_at',
								// 'update_at',
								// ['attribute'=>'expected_start_date','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
								// ['attribute'=>'expected_end_date','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
								// ['attribute'=>'actual_start_date','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
								// ['attribute'=>'actual_end_date','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
								// 'project_description:ntext',
								// 'project_budget',
								// 'project_currency',
								// 'customer_id',
								
								/*
								 * [
								 * 'attribute' => 'customer_id',
								 * 'label' => 'Customer',
								 * 'filterType' => GridView::FILTER_SELECT2,
								 * 'format' => 'raw',
								 * 'width' => '150px',
								 * 'filter' => ArrayHelper::map ( Customer::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'customer_name' ),
								 * 'filterWidgetOptions' => [
								 * 'options' => [
								 * 'placeholder' => 'All...'
								 * ],
								 * 'pluginOptions' => [
								 * 'allowClear' => true
								 * ]
								 * ],
								 * 'value' => function ($model, $key, $index, $widget)
								 * {
								 * // var_dump($model->user);
								 * if (isset ( $model->customer ) && ! empty ( $model->customer->first_name ))
								 * return $model->customer->customer_name;
								 * }
								 * ],
								 */
								[ 
										'attribute' => 'id',
										'label' => Yii::t('app', 'Tasks/Defects Status'),
										'format' => 'raw',
										'width' => '150px',
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->user);
											if (isset ( $model->opentask ))
												return '<a href="?r=pmt/project/project-view&id=' . $model->id . '&tasktab=true" class="btn btn-xs btn-success" >Open Task <span class="badge">' . $model->opentask . '</span></a> <br/><br/><a href="?r=pmt/project/project-view&id=' . $model->id . '&defecttab=true" class="btn btn-xs btn-success" >Open Defect <span class="badge">' . $model->opendefect . '</span></a>';
										} 
								],
								[ 
										'attribute' => 'id',
										'label' => Yii::t('app', 'Assigned Users'),
										'format' => 'raw',
										'width' => '100px',
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->user);
											/*if (isset ( $model->users ))
												return '<a href="?r=pmt/project/project-view&id=' . $model->id . '&joined_user=true" ><span class="badge">' . $model->users . '</span></a> <br/>';*/
												if(Yii::$app->params['USER_IMAGE'] =='Yes'){
												$users='<div class="project-people">';
													foreach(Project::getProjectUsers($model->id) as $p_user){
														$path='../users/'.$p_user['user_id'].'.png';
														if(file_exists($path)){
															$image='<img  class="img-circle"  src="../users/'.$p_user['user_id'].'.png">';								
														 }else{ 
															$image='<img   class="img-circle" src="../users/nophoto.jpg">';
														 }
														$users.=' <a href="index.php?r=user/user/update&id='.$p_user['user_id'].'&edit=t">'.$image.'</a>';	
													}
												$users.='</div>';
												return $users;
												}else{
													$users='';
													foreach(Project::getProjectUsers($model->id) as $p_user){
														$users.=' <a href="index.php?r=user/user/update&id='.$p_user['user_id'].'&edit=t" class="btn btn-primary btn-xs" style="margin-bottom:5px">'.Project::getUserName($p_user['user_id']).'</a>';	
													}
												$users.='';
												return $users;
												}
										} 
								],
		/*
		[ 
				'attribute' => 'expected_start_date',
				'label' => 'Start Date',
				'filterType' => GridView::FILTER_DATE,
										'width' => '150px',
										'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
										]
		],
		*/
		
		
		/*[ 
										'attribute' => 'expected_end_datetime',
										'label' => 'ETA',
										'filterType' => GridView::FILTER_DATE,
										'width' => '150px',
										'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
										] ,
										'value' => function ($model, $key, $index, $widget) {
										if(isset($model->expected_end_datetime)) 
											return date('F d,Y',strtotime($model->expected_end_datetime));
										} 
								],*/
								[ 
										'attribute' => 'project_progress',
										//'width'=>'80px',
										'label' => Yii::t('app', 'Progress'),
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return "<div class='progress'>
  <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='" . $model->project_progress . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $model->project_progress . "%'>" . $model->project_progress . "</div>
</div>";
										} 
								],
								
								// 'project_status_id',
								
								[ 
										'class' => '\kartik\grid\ActionColumn',
										'template' => '{update} {view} {delete}',
										'buttons' => [ 
												'update' => function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/project/project-view',
															'id' => $model->id 
													] ), [ 
															'title' => Yii::t('app', 'Edit' ) 
													] );
												},
												'view' => function ($url, $model)
												{
													return '';
												
												} ,
												'delete' => function ($url, $model)
												{
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
								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app','Projects'). ' </h3>',
								'type' => 'info',
								'before' =>'<a href="index.php?r=pmt/project/create&customer_id='.$model->id.'" class="btn btn-success  btn-sm"> <i class="glyphicon glyphicon-plus"></i> '. Yii::t('app','Add').'</a>',
								'after' => '',
								'showFooter' => false 
						],
						'toolbar' => [
					//'{toggleData}',
				//	'{export}',
				],
				
				] );
				Pjax::end ();
				?>
</div>
