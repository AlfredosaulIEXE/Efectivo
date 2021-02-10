<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;

use yii\helpers\ArrayHelper;

?>

    <?php 

	if((isset($_SESSION['queue_owner_user_id']) && $_SESSION['queue_owner_user_id'] ==Yii::$app->user->identity->id ) || Yii::$app->params['user_role']=='admin'){

		$btn='<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.exist_users\').modal(\'show\');"><i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'Add User to Queue').'</a>';

	}

	Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProviderUser,

        //'filterModel' => $searchModelUser,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



  //          'id',

            //'task_id',

            //'task_name',,

								[ 

										'attribute' => 'user_id',

										'label' => Yii::t('app', 'Image'),

										'format' => 'raw',

										'width' => '50px',

										'value' => function ($model, $key, $index, $widget)

										{

												$users='<div class="project-people">';

														$path='../users/'.$model->user_id.'.png';

														if(file_exists($path)){

															$image='<img  src="../users/'.$model->user_id.'.png">';								

														 }else{ 

															$image='<img src="../users/nophoto.jpg">';

														 }

														$users.=' <a href="javascript:void(0)" onClick="showPopup(\''.$model->user_id.'\')">'.$image.'</a>';	

												$users.='</div>';

												return $users;

										} 

								],

			

			

			 

			[ 

				'attribute' => 'user_id',

				'label' =>Yii::t('app', 'First Name') ,

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->first_name)) 

					return $model->user->first_name;

				} 

			],

			[ 

				'attribute' => 'user_id',

				'label' => Yii::t('app', 'Last Name'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->first_name)) 

					return $model->user->last_name;

				} 

			],

			[ 

				'attribute' => 'user_id',

				'label' => Yii::t('app', 'Username'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'filter' => ArrayHelper::map (User::find()->orderBy ( 'id' )->asArray ()->all (), 'id', 'first_name' ),

				'filterWidgetOptions' => [ 

						'options' => [ 

								'placeholder' => 'All...' 

						],

						'pluginOptions' => [ 

								'allowClear' => true 

						] 

				],

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->first_name)) 
					
					return '<a href="javascript:void(0)" onClick="showPopup(\''.$model->user_id.'\')">'.$model->user->username.'</a>';

				} 

			],
			
			[
				'attribute' => 'user_id',
				'label' => 'User Type',
				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->user_type_id)) 

					return $model->user->userType->label;

				}
				
				
			],
			
			[ 

				'attribute' => 'user_id',

				'label' => Yii::t('app', 'Email'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '25%',

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->user) && !empty($model->user->email)) 

					return $model->user->email;

				} 

			],

			/*[ 

					'attribute' => 'added_at',
					'label' => Yii::t('app', 'Added At'),

					'width' => '20%'  ,
					'format' => 'raw',

					'value' => function ($model, $key, $index, $widget) {

						return date('jS \of M Y H:i:s',$model->added_at);
					}

			],*/

			[ 



										'class' => '\kartik\grid\ActionColumn',



										'template' => '{update} {view} {delete}',



										'buttons' => [ 



												'update' => function ($url, $model)

													{

													return '';

												},



												'view' => function ($url, $model)

													{

													return '';

												},

												'delete' => function ($url, $model)



												{

												if($_SESSION['queue_owner_user_id'] ==Yii::$app->user->identity->id || Yii::$app->params['user_role']=='admin'){

													return Html::a ( '<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl ( [ 



															'liveobjects/queue/update',



															'id' => $_GET['id'],'udel'=>$model->id 



													] ), [ 



															'title' => Yii::t('app', 'Delete' ) ,

															'onClick'=>"return confirm('Are you Sure!')"



													] );

												}else{

													return '';	

												}



												}



										]



										 



								] 



        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Users in this Queue').'</h3>',

            'type'=>'info',

            'before'=>$btn,                                                                                                                                                 /*         'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>