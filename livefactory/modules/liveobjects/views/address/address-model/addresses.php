<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\Country;

use livefactory\models\State;

use livefactory\models\City;

use yii\helpers\ArrayHelper;
function isPrimary1($id){
	$sql="select * from  tbl_address  where id='$id' and is_primary=1"; 
	$connection = \Yii::$app->db;
	$command=$connection->createCommand($sql);
	$address=$command->queryAll();
	return count($address);
}
?>

    <?php 

	 Yii::$app->request->enableCsrfValidation = true;

    $csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');

	Pjax::begin(); echo GridView::widget([

        'dataProvider' => $dataProviderAddresses,

		'responsive' => true,'responsiveWrap' => false,

        //'filterModel' => $searchModelAttch,

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],



  //          'id',

            //'task_id',

            //'task_name',

			[ 

					'attribute' => 'address_1',
					'label'=>Yii::t('app', 'Address 1'),

					'width' => '25%',

					'format' => 'raw'

			],

			[ 

					'attribute' => 'address_2',
					'label'=>Yii::t('app', 'Address 2'),

					'width' => '20%',

					'format' => 'raw'

			],


			

			 

			[ 

				'attribute' => 'country_id',
					'label'=>Yii::t('app', 'Country'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '10%',

				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->country) && !empty($model->country->country)) 

					return $model->country->country;

				} 

		],

		[ 

				'attribute' => 'state_id',
				'label'=>Yii::t('app', 'State'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '10%',
				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->state) && !empty($model->state->state)) 

					return $model->state->state;

				} 

		],

		[ 

				'attribute' => 'city_id',
				'label'=>Yii::t('app', 'City'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '10%',
				'value' => function ($model, $key, $index, $widget) {

					//var_dump($model->user);

				if(isset($model->city) && !empty($model->city->city)) 

					return $model->city->city;

				} 

		],

		[ 

					'attribute' => 'zipcode',
					'label'=>Yii::t('app', 'Zipcode'),

					'width' => '10%',

			],
	
			

			[ 
					'label'=>Yii::t('app','Is Primary'),
					'width' => '10%',
					'attribute' => 'id',
					'format'=>'raw',
					'value'=>function($model){
						if(isPrimary1($model->id)){
							return '<span class="label label-primary">'.Yii::t('app','Primary').'</span>	';
						}else{
							return '<span class="label label-danger">'.Yii::t('app','Secondary').'</span>	';
						}
					}

			],

            [

               'class' => '\kartik\grid\ActionColumn',

				//'template'=>'{view}{update}{delete}',

				//'class'=>'CButtonColumn',

				// 'class' => ActionColumn::className(),

    			'template'=>'{update}  {delete}  {primary}',

                'buttons' => [

				'width' => '10%',

                'update' => function ($url, $model) {

                                    return"<form name='frm_address".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&address_edit=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>

									<a href='#' onClick='document.frm_address".$model->id.".submit()' title='".Yii::t('app', 'Edit')."' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";},

									'delete' => function ($url, $model) {

                                   

							},

							 'delete' => function ($url, $model)
                    
                    {
                        
                        if(isPrimary1($model->id)){
                            
                            return '';
                        } else {
                            
                            return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&address_del='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete').'"><span class="glyphicon glyphicon-trash"></span></a>';
                        }
                    },

												  'primary'=>function($url,$model){
														  if(!isPrimary1($model->id)){
															   return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&address_primary='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Make Primary').'"><span class="fa fa-diamond"></span></a>';
														  }else{
															 return ''; 
														  }
												  }

				



                ],

            ],

        ],

        'responsive'=>true,

        'hover'=>true,

        'condensed'=>true,

        //'floatHeader'=>true,









        'panel' => [

            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Addresses').'  </h3>',

            'type'=>'info',

            'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.addressae\').modal(\'show\');"><i class="glyphicon glyphicon-road"></i> '.Yii::t('app', 'New Address').'</a>',  
			        /*                                                                                                                                                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

            'showFooter'=>false

        ],

    ]); Pjax::end(); ?>
	
	<script>
	function get_confirm(){
		return confirm("<?=Yii::t ('app','Are you Sure!')?>");
	}
	</script>
