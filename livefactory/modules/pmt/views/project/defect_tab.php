<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\DefectPriority;
use livefactory\models\DefectStatus;
use livefactory\models\Project;
use livefactory\models\DefectType;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Defect $searchModel
 */


?>


    <?php
	Yii::$app->request->enableCsrfValidation = true;
    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');			
Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProviderDefect,
						//'filterModel' => $searchModelDefect,
						'pjax'=>true,
						'columns' => [ 
								[ 
										'class' => 'yii\grid\SerialColumn' 
								],
								
								[ 
										'attribute' => 'defect_id',
										'width' => '100px',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return '<a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'">'.$model->defect_id.'</a>';
										} 
								],
								[ 
										'attribute' => 'defect_name',
										'label'=>'Defect Title',
										'width' => '500px',
										'format' => 'raw',
										'value' => $model->defect_name
								],
								

								[ 
										'attribute' => 'user_assigned_id',
										'label' => Yii::t('app','User'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '200px',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->asArray ()->all (), 'id', 'first_name' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...')//'All...' 
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
											if($model->actual_start_datetime=='0000-00-00 00:00:00'){
												return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
											}else{
											return date('jS \of M Y H:i:s',strtotime($model->actual_start_datetime));
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
											if($model->actual_end_datetime=='0000-00-00 00:00:00'){
												return '<i class="not-set">'.Yii::t('app', 'not set').'</i>';
											}else{

											return date('jS \of M Y H:i:s',strtotime($model->actual_end_datetime));
												}
											}
										} 
								],
								
								// 'defect_description:ntext',
								// ['attribute'=>'actual_end_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'actual_start_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'time_spent',
								// 'assigned_user_id',
								
								// 'payment_rate',
								// 'project_id',
								// ['attribute'=>'expected_start_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'expected_end_datetime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'defect_status_id',
								// 'defect_priority_id',
								/*[ 
										'attribute' => 'project_id',
										'label' => Yii::t('app', 'Project'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '120px',
										'filter' => ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->asArray ()->all (), 'id', 'project_name' ),
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
											// var_dump($model->defectPriority);
											if (isset ( $model->project ) && ! empty ( $model->project->project_name ))
												return $model->project->project_name;
										} 
								],*/
								[ 
										'attribute' => 'defect_status_id',
										'label' => Yii::t('app', 'Status'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( DefectStatus::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...')//'All...' 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->defectPriority);
											if (isset ( $model->defectStatus ) && ! empty ( $model->defectStatus->label ))
												return statusLabel ( $model->defectStatus->label );
										} 
								],
								[ 
										'attribute' => 'defect_priority_id',
										'label' => Yii::t('app','Priority'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '100px',
										'filter' => ArrayHelper::map ( DefectPriority::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...')//'All...' 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
											// var_dump($model->defectPriority);
											if (isset ( $model->defectPriority ) && ! empty ( $model->defectPriority->label ))
												return statusLabel ( $model->defectPriority->label );
										} 
								],
								
								// ['attribute'=>'date_added','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// ['attribute'=>'date_modified','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
								// 'progress',
								[ 
										'attribute' => 'defect_progress',
										'label' =>Yii::t('app', 'Progress') ,
										'format' => 'raw',
										'width' => '100px',
										'value' => function ($model, $key, $index, $widget)
										{
											return "<div class='progress'>
  <div class='progress-bar progress-bar-info progress-bar-striped' role='progressbar' aria-valuenow='" . $model->defect_progress . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $model->defect_progress . "%'>" . $model->defect_progress . "</div>
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
													/*return"<form name='frm_defect".$model->id."' action='index.php?r=pmt/defect/defect-view&id=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>
									<a href='#' onClick='document.frm_defect".$model->id.".submit()' title='View' target='_parent'><span class='glyphicon glyphicon-eye-open'></span></a></form>";*/
									return '<a href="index.php?r=pmt/defect/defect-view&id='.$model->id.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
													/*return Html::a ( '<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/defect/defect-view',
															'id' => $model->id 
													] ), [ 
															'title' => Yii::t('app', 'Edit' ) 
													] );*/
												} ,
											'delete' => function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl ( [ 
															'pmt/project/project-view',
															'defect_del' => $model->id,
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
								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Defects').' </h3>',
								'type' => 'info',
								'showFooter' => false ,
								'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.defectae\').modal(\'show\');"><i class="fa fa-bug"></i> '.Yii::t('app', 'New Defect').'  </a>'
						] 
				] );
				Pjax::end ();
				?>
