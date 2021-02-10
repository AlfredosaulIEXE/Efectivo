<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\Country;

use livefactory\models\State;

use livefactory\models\City;

use yii\helpers\ArrayHelper;



/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var livefactory\models\search\Address $searchModel

 */



$this->title = Yii::t ( 'app', 'Addresses' );

$this->params ['breadcrumbs'] [] = $this->title;
//var_dump();
?>
<?php
	if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?= Yii::t ( 'app','Address is Added')?></div>
<?php	}
?>

    <?php Yii::$app->request->enableCsrfValidation = true; ?>

<div class="address-index">
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

				       * 'modelClass' => 'Address',

				       * ]), ['create'], ['class' => 'btn btn-success'])

				       */

								?>
  <!--  </p> -->

    <?php

			if(!empty($_REQUEST['Address']["country_id"])){
									$c_id=$_REQUEST['Address']["country_id"];
									$states=ArrayHelper::map ( State::find ()->orderBy ( 'state' )->asArray ()->where("country_id=$c_id  and active=1")->all (), 'id', 'state' );
								}else{
									$states=ArrayHelper::map ( State::find ()->orderBy ( 'state' )->asArray ()->where("id=0 ")->all (), 'id', 'state' );
								}	
			if(!empty($_REQUEST['Address']["state_id"])){
									$s_id=$_REQUEST['Address']["state_id"];
									$cities=ArrayHelper::map ( City::find ()->orderBy ( 'city' )->asArray ()->where("state_id=$s_id  and active=1")->all (), 'id', 'city' );
								}else{
									$cities=ArrayHelper::map ( City::find ()->orderBy ( 'city' )->asArray ()->where("id=0")->all (), 'id', 'city' );
								}	

Pjax::begin ();

				echo GridView::widget ( [ 

						'dataProvider' => $dataProvider,

						'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,

						'columns' => [ 
								['class' => '\kartik\grid\CheckboxColumn'],

								[ 

										'class' => 'yii\grid\SerialColumn' 

								],

								

								// 'id',

								'address_1',

								'address_2',

								[ 

										'attribute' => 'country_id',

										//'label' => 'Country',

										'filterType' => GridView::FILTER_SELECT2,

										'format' => 'raw',

										'width' => '150px',

										'filter' => ArrayHelper::map ( Country::find ()->where(" active=1")->orderBy ( 'country' )->asArray ()->all (), 'id', 'country' ),

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

										'width' => '150px',

										'filter' => $states,

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

								

								[ 

										'attribute' => 'city_id',

										//'label' => 'City',

										

										'filterType' => GridView::FILTER_SELECT2,

										'format' => 'raw',

										'width' => '150px',

										

										 'filter' => $cities,

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

											if (isset ( $model->city ) && ! empty ( $model->city->city ))

												return $model->city->city;

										} 

								],

								

								'zipcode',

								

								// 'created_at',

								// 'updated_at',

								

								[ 

										'class' => '\kartik\grid\ActionColumn',
										'header'=>'Actions',
										'template' => '{view} {update} {delete}',

										'buttons' => [ 

												'update' => function ($url, $model)

												{

													return Html::a ( '<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl ( [ 

															'liveobjects/address/update',

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

</div>

<script>

	function all_del(){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			document.frm.submit()

		} else {

			

		}	

	}

</script>