<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\Country;

use livefactory\models\State;

use yii\helpers\ArrayHelper;

use livefactory\models\City;


/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var livefactory\models\search\City $searchModel

 */



$this->title = Yii::t ( 'app', 'Cities' );

$this->params ['breadcrumbs'] [] = $this->title;

if(!empty($_REQUEST['City']["country_id"])){
		$c_id=$_REQUEST['City']["country_id"];
		$states=ArrayHelper::map ( State::find ()->orderBy ( 'state' )->asArray ()->where("country_id=$c_id and active=1")->all (), 'id', 'state' );
	}else{
		$states=ArrayHelper::map ( State::find ()->orderBy ( 'state' )->asArray ()->where("id=0")->all (), 'id', 'state' );
	}
function statusLabel($status)
{
	if ($status !='1')
	{
		$label = "<span class=\"label label-danger\">".Yii::t('app', 'Inactive')."</span>";
	}
	else
	{
		$label = "<span class=\"label label-primary\">".Yii::t('app', 'Active')."</span>";
	}
	return $label;
}
$status = array('0'=>Yii::t('app', 'Inactive'),'1'=>Yii::t('app', 'Active'));
?>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=Yii::t ( 'app','City is Added')?> </div>
<?php	}
?>
<div class="city-index">
	<!--
	<div class="page-header">

		<h1><?= Html::encode($this->title) ?></h1>

	</div>
	-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p> -->
        <?php

								/*

								 * echo Html::a(Yii::t('app', 'Create {modelClass}', [

								 * 'modelClass' => 'City',

								 * ]), ['create'], ['class' => 'btn btn-success'])

								 */

								?>
    <!--</p> -->

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

								'city',

								//'city_code',

								[ 

										'attribute' => 'country_id',

										//'label' => 'Country',

										'filterType' => GridView::FILTER_SELECT2,

										'format' => 'raw',

										'width' => '350px',

										'filter' => ArrayHelper::map ( Country::find ()->where("active=1")->orderBy ( 'country' )->asArray ()->all (), 'id', 'country' ),

										'filterWidgetOptions' => [ 

												'options' => [ 

														'placeholder' => Yii::t ( 'app','All...')

												],

												'pluginOptions' => [ 

														'allowClear' => true 

												] 

										],

										'value' => function ($model, $key, $index, $widget)

										{

											// var_dump($model->user);

											if (isset ( $model->country ) && ! empty ( $model->country->country ))

												return $model->country->country;

										} 

								],

								

								[ 

										'attribute' => 'state_id',

										//'label' => 'State',

										'filterType' => GridView::FILTER_SELECT2,

										'format' => 'raw',

										'width' => '350px',

										'filter' =>  $states,

										'filterWidgetOptions' => [ 

												'options' => [ 

														'placeholder' => Yii::t ( 'app','All...') 

												],

												'pluginOptions' => [ 

														'allowClear' => true 

												] 

										],

										'value' => function ($model, $key, $index, $widget)

										{

											if (isset ( $model->state ) && ! empty ( $model->state->state ))

												return $model->state->state;

										} 

								],

								

								//'active',
								[ 
										'attribute' => 'active',
										//'label' => 'Active',
										'format' => 'raw',
										'filterType' => GridView::FILTER_SELECT2,
										'filter' => $status,
										'filterWidgetOptions' => [ 
												'options' => [ 
														'placeholder' => Yii::t ( 'app','All...') 
												],
												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
										'value' => function ($model, $key, $index, $widget)
										{
												return statusLabel ( $model->active );
										} 
								],

								

								// 'added_at',

								// 'updated_at',

								


								[ 

										'class' => '\kartik\grid\ActionColumn',
										'template'=>'{update} ',
										'buttons' => [ 

												'update' => function ($url, $model)

												{

													return Html::a ( '<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl ( [ 

															'liveobjects/city/update',

															'id' => $model->id,

															'edit' => 't' 

													] ), [ 

															'title' => Yii::t('app', 'Edit' ) 

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

								'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode ( $this->title ) . ' </h3>',

								'type' => 'info',

								'before' => Html::a ( '<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), [ 

										'create' 

								], [ 

										'class' => 'btn btn-success btn-sm' 

								] ),

								'after' => Html::a ( '<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app', 'Reset List'), [ 

										'index' 

								], [ 

										'class' => 'btn btn-info btn-sm' 

								] ),

								'showFooter' => false 

						] 

				] );

				Pjax::end ();

				?>

</div>

