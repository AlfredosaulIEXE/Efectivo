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


$this->title = Yii::t ( 'app', 'User Session Activity' );
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="user-index">
    <?php 
				
				Pjax::begin ();
				echo GridView::widget ( [ 
						'dataProvider' => $dataProvider,
						//'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
						'columns' => [ 
								[ 
										'class' => 'yii\grid\SerialColumn' 
								],
								
								// 'id',
								[ 
										'attribute' => 'user_id',
										'label' => 'User Name',
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
								'entity_type',
								
								[ 
										'attribute' => 'notes',
										'format' => 'raw' 
								],
								[ 
										'attribute' => 'added_at',
										'format' => 'raw',
										'value' => function ($model, $key, $index, $widget)
										{
												return date('jS \of M Y H:i:s',$model->added_at);
											
										} 
								]
								 
						],
						'responsive' => true,
						'hover' => true,
						'condensed' => true,
						'floatHeader' => false,
						
						'panel' => [ 
								'heading' => '<i class="glyphicon glyphicon-th-list"></i> ' . Html::encode ( $this->title ),
								'type' => 'info',
								'showFooter' => false 
						] 
				] );
				Pjax::end ();
				?>

</div>
