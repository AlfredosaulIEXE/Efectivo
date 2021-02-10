<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\User $searchModel
 */
use livefactory\models\TimeDiffModel;
date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
?>
<?php


$this->title = Yii::t ( 'app', 'User Session' );
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="user-index">
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
								[ 
										'attribute' => 'user_id',
										//'label' => 'Type',
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										'width' => '15%',
										'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'id' )->asArray ()->all (), 'id',
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
											if (isset ( $model->user ) && ! empty ( $model->user->first_name )){
											$username=$model->user->username?$model->user->username:$model->user->email;
												return $model->user->first_name.' '.$model->user->last_name." (  <a href='index.php?r=user/user/view&id=".$model->user->id."'>".$model->user->username."</a> ) ";
											}
										} 
								],
								[ 
										'attribute' => 'logged_in',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											return date('jS \of M Y H:i:s',($model->logged_in));
										} 
								],
								[ 
										'attribute' => 'logged_out',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
											if($model->logged_out ==''){
												return Yii::t ( 'app', 'not set' );
												
											}else{
												return date('jS \of M Y H:i:s',($model->logged_out));
											}
										} 
								],
								'location_ip',
								'session_id',

								[ 
										'attribute' => 'last_logged',
										'label'=> Yii::t ( 'app', 'Status' ),
										'format' => 'raw',
										/*'filterType' => GridView::FILTER_SELECT2,
										'filter' =>array(null=>Yii::t ( 'app', 'Logged In' ),'Logged Out'=>Yii::t ( 'app', 'Logged Out' )),
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t('app', 'All...')
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										*/
										'value' => function ($model, $key, $index, $widget)
										{
											if($model->logged_out ==''){
												return "<span class=\"label label-primary\">".Yii::t ( 'app', 'Logged In' )."</span>";
												
											}else{
												return "<span class=\"label label-danger\">".Yii::t ( 'app', 'Logged Out' )."</span>";
											}
										} 
								],
								
								

								[ 
										'class' => '\kartik\grid\ActionColumn',
										'template'=>'{time}',
										'header'=>Yii::t ( 'app', 'Duration' ),
										'width' => '10%',
										'buttons' => [ 
												'time' =>function ($url, $model)
												{
													date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
													if($model->logged_out =='' || $model->logged_out==''){
														return TimeDiffModel::dateDiff(date('Y-m-d H:i:s'),date('Y-m-d H:i:s', $model->logged_in));
														
													}else{
														return TimeDiffModel::dateDiff(date('Y-m-d H:i:s', $model->logged_out),date('Y-m-d H:i:s', $model->logged_in));
													}	
												},
												]
								],
								
								[ 
										'class' => '\kartik\grid\ActionColumn',
										'template'=>'{update} {view} ',
										'width' => '10%',
										'header'=>Yii::t ('app', 'Actions'),
										'buttons' => [ 
												'delete' =>function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-trash"> </span>'.Yii::t('app', 'Delete' ), Yii::$app->urlManager->createUrl ( [ 
															'user/user/user-sessions',
															'del_id' => $model->id
													] ), [ 
															'title' => Yii::t('app', 'Delete Session' ) ,
															'onClick'=>"return confirm('Are you sure!')",
															'class'=>'btn btn-danger btn-xs'
													] );	
												},
												'update' =>function ($url, $model)
												{
												return'';	
												},
												'view' => function ($url, $model)
												{
													return Html::a ( '<span class="glyphicon glyphicon-eye-open"></span> '.Yii::t ( 'app', 'Activities' ).' ', Yii::$app->urlManager->createUrl ( [ 
															'user/user/user-session-detail',
															'id' => $model->user_id,
															'start'=>$model->logged_in,
															'end'=>$model->logged_out,
															'session_id'=>$model->session_id /* new session_id added in tbl_history */
													] ), [ 
															'title' => Yii::t('app', 'View' ),
															'class'=>'btn btn-primary btn-xs' 
													] );
												} 
										] 
								]
								 
						],
						'responsive' => true,
						'hover' => true,
						'condensed' => true,
						'floatHeader' => false,
						
						'panel' => [ 
								'heading' => '<i class="glyphicon glyphicon-th-list"></i> ' . Html::encode ( $this->title ),
								'type' => 'info',
								/*'before' => Html::a ( '<i class="glyphicon glyphicon-plus"></i> Add', [ 
										'create' 
								], [ 
										'class' => 'btn btn-success' 
								] ),
								'after' => Html::a ( '<i class="glyphicon glyphicon-repeat"></i> Reset List', [ 
										'index' 
								], [ 
										'class' => 'btn btn-info' 
								] ),*/
								'showFooter' => false 
						] 
				] );
				Pjax::end ();
				?>

</div>
