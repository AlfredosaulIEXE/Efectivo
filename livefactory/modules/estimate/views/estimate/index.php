<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use livefactory\models\Customer;
use livefactory\models\Currency;
use livefactory\models\DiscountType;
use livefactory\models\EstimateStatus;
use livefactory\models\search\Estimate as EstimateSearch;
use livefactory\models\Lead;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Estimate $searchModel
 */

if($_REQUEST['r'] == 'estimate/estimate/index')
{
	$this->title = Yii::t('app', 'Estimates');
	$this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="estimate-index">

    <?php 

	if($_REQUEST['entity_type'] == 'customer')
	{
		$entity='customer';
		$label='Customer';
		$array=ArrayHelper::map(Customer::find()->all(),'id','customer_name');
	}
	else if($_REQUEST['entity_type'] == 'lead')
	{
		$entity='lead';
		$label='Lead';
		$array=ArrayHelper::map(Lead::find()->all(),'id','lead_name');
	}
	else if ($model->entity_type == 'customer')
	{
		$entity='customer';
		$label='Customer';
		$array=ArrayHelper::map(Customer::find()->all(),'id','customer_name');
	}
	else if ($model->entity_type == 'lead')
	{
		$entity='lead';
		$label='Lead';
		$array=ArrayHelper::map(Lead::find()->all(),'id','lead_name');
	}
	else if ($_REQUEST['r'] == 'customer/customer/customer-view')
	{
		$entity='customer';
	}
	else if ($_REQUEST['r'] == 'sales/lead/view')
	{
		$entity='lead';
	}

	if ($_REQUEST['r'] == 'estimate/estimate/index')
		$dataProvider = (new EstimateSearch) -> searchWithEntity($entity,Yii::$app->request->getQueryParams());

	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
		'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
			[
				'attribute'=>'estimation_code',
				'format' => 'raw',
				'width'=>'10%',
				'value'=>function($model){
					return '<a href="index.php?r=estimate/estimate/view&id=' . $model->id . '">' . $model->estimation_code . '</a>';
				}
				],
           
           /// 'customer_id',
            [
				'attribute'=>'customer_id',
				'filterType' => GridView::FILTER_SELECT2,
				'filter'=>$array,
				'width'=>'20%',
				'format' => 'raw',
				'filterWidgetOptions' => [ 
											'options' => [ 
													'placeholder' => Yii::t('app', 'All...') 
												],

												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
				'value'=>function($model){
					if($model->entity_type == 'customer')
					{
						return '<a href="index.php?r=customer/customer/customer-view&id=' . $model->customer->id . '">' . $model->customer->customer_name . '</a>';
					}
					else if($model->entity_type == 'lead')
					{
						return '<a href="index.php?r=sales/lead/view&id=' . $model->lead->id . '">' . $model->lead->lead_name . '</a>';
					}
				}
				],
				[
				'attribute'=>'date_issued',
				'filterType' => GridView::FILTER_DATE,
				'width'=>'20%',
				'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
											],
				'value'=>function($model){
					return date('d-M-Y',($model->date_issued));
				}
			],
				 [
				'attribute'=>'currency_id',
				'width'=>'10%',

										'filterType' => GridView::FILTER_SELECT2,
				'filter'=>ArrayHelper::map(Currency::find()->all(),'id','currency'),

										'filterWidgetOptions' => [ 

												'options' => [ 

														'placeholder' => Yii::t('app', 'All...') 

												],

												'pluginOptions' => [ 

														'allowClear' => true 

												] 

										],
				
				'value'=>function($model){
					return $model->currency->currency;
				}
				],
        
            'grand_total', 
			['attribute'=>'estimate_status_id',
					'filterType' => GridView::FILTER_SELECT2,
						'format' => 'raw',
						'width'=>'20%',
						'filter' => ArrayHelper::map(EstimateStatus::find()->asArray()->all(), 'id', 'label'),
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
                    if($model->estimate_status_id == EstimateStatus::_CREATED)
                    	return "<span class=\"label label-info\">" . $model->estimateStatus->label . "</span>";
					else if($model->estimate_status_id == EstimateStatus::_SENTFORAPPROVAL)
						return "<span class=\"label label-warning\">" . $model->estimateStatus->label . "</span>";
					else if($model->estimate_status_id == EstimateStatus::_APPROVED)
						return "<span class=\"label label-primary\">" . $model->estimateStatus->label . "</span>";
					else if($model->estimate_status_id == EstimateStatus::_REJECTED)
						return "<span class=\"label label-danger\">" . $model->estimateStatus->label . "</span>";
					else
						return "<span class=\"label label-danger\">" . $model->estimateStatus->label . "</span>";
						
                }],


            [
                'class' => '\kartik\grid\ActionColumn',
				'header'=>Yii::t('app','Action'),
				'width'=>'10%',
				'template' => '{download} {view} {update} {delete}',
                'buttons' => [
				
				 'download' => function ($url, $model) {
					                return Html::a('<span class="fa fa-file-pdf-o"></span>', Yii::$app->urlManager->createUrl(['estimate/estimate/download','id'=>$model->id]),
									[ 'title' => Yii::t('app', 'Download Estimate'),'data-pjax'=>"0" ]);
												  },
				
                'update' => function ($url, $model) {
														if(Yii::$app->user->identity->userType->type=="Customer")
														{
															return '';
														}
														else
														{
																		return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['estimate/estimate/update','id' => $model->id,'edit'=>'t', 'entity_type'=>$model->entity_type]), [
																						'title' => Yii::t('app', 'Edit'),
																					  ]);
														}
													},
				 'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['estimate/estimate/view','id' => $model->id, 'entity_type'=>$model->entity_type]), [
                                                    'title' => Yii::t('app', 'View'),
                                                  ]);},
				'delete' => function ($url, $model) {
										if(Yii::$app->user->identity->userType->type=="Customer")
										{
											return '';
										}
										else
										{
											if($_REQUEST['r'] != 'estimate/estimate/index')
											{
												return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&estimate_del='.$model->id.'" onClick="return confirm(\'Are you Sure!\')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
											}
											else
											{
												return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['estimate/estimate/delete','id' => $model->id, 'entity_type' => $model->entity_type]), [
															'title' => Yii::t('app', 'Delete'),
															'data' => [                          
																		'method' => 'post',                          
																		'confirm' => Yii::t('app', 'Are you sure?')],
												]);
															
											}
										}
									}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Yii::$app->user->identity->userType->type=="Customer"?'':'<a href="index.php?r=estimate/estimate/create&entity_type='. $entity .'" class="btn btn-success  btn-sm"> <i class="glyphicon glyphicon-plus"></i> '. Yii::t('app','Add').'</a>', 
			//Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success btn-sm']),
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info btn-sm']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
