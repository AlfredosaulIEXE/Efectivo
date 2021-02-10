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

//$this->title = Yii::t ( 'app', 'Projects' );
//$this->params ['breadcrumbs'] [] = $this->title;

function statusLabel1($status)
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
function getType1($id,$user_id){
	$connection = \Yii::$app->db;
		$sql="select * from tbl_project where id=$id and project_owner_id=$user_id";
		$command=$connection->createCommand($sql);
		$dataReader=$command->queryAll();
		if(count($dataReader) > 0){
			return "Project Owner";
		}else{
			return "Project User";
		}
}
?>
<div class="project-index">
    <?php
				
Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProvider,
					//	'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
						'columns' => [ 
								[ 
										'class' => 'yii\grid\SerialColumn' 
								],
								
								// 'id',
								// 'parent_project_id',
								
								// 'project_name',
								[ 
										'attribute' => 'id',
										'label' => Yii::t('app', 'Role'),
										'format' => 'raw',
										'width' => '100px',
										'value' => function ($model, $key, $index, $widget)
										{
											return getType1($model->id,$_GET['id']);
										} 
								],
								
								[ 
										'attribute' => 'project_name',
										'width' => '200px',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return '<a href="?r=pmt/project/project-view&id=' . $model->id . '">' . $model->project_name . '</a>';
										} 
								],
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
										'attribute' => 'customer_id',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'filter' => ArrayHelper::map ( Customer::find ()->orderBy ( 'customer_name' )->asArray ()->all (), 'id', 'customer_name' ),
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
											if (isset ( $model->customer ) && ! empty ( $model->customer->customer_name ))
												return '<a href="index.php?r=customer/customer/customer-view&id='.$model->customer->id.'">'.$model->customer->customer_name.'</a>';
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
												return statusLabel1 ( $model->status->label );
										} 
								],
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
						],
						'responsive' => true,
						'hover' => true,
						'condensed' => true,
						'floatHeader' => false,
						'toolbar'=>false,
						
						'panel' => [ 
								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' .Yii::t ( 'app', 'Projects' ). ' </h3>',
								'type' => 'info',
								'before' => '',
								 'after'=>'',
            'showFooter'=>false
						] 
				] );
				Pjax::end ();
				?>
</div>
