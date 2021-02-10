<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use livefactory\models\Customer;
use livefactory\models\Currency;
use livefactory\models\DiscountType;
use livefactory\models\InvoiceStatus;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Invoice $searchModel
 */

$this->title = Yii::t('app', 'Invoice');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			// 'id',
            //'invoice_number',
			[
				'attribute'=>'invoice_number',
				'format' => 'raw',
				'value'=>function($model){
					return '<a href="index.php?r=invoice/invoice/view&id=' . $model->id . '">' . $model->invoice_number . '</a>';
				}
			],
			[
				'attribute'=>'customer_id',
				'format' => 'raw',
				'width' => '250px',
				'filterType' => GridView::FILTER_SELECT2,
				'filter'=>ArrayHelper::map(Customer::find()->all(),'id','customer_name'),
				'filterWidgetOptions' => [ 
											'options' => [ 
													'placeholder' => Yii::t('app', 'All...') 
												],

												'pluginOptions' => [ 
														'allowClear' => true 
												] 
										],
				'value'=>function($model){
											return '<a href="index.php?r=customer/customer/customer-view&id=' . $model->customer->id . '">' . $model->customer->customer_name . '</a>';
										}
			],
            'po_number',
			
			'tax_number',

			[
				'attribute'=>'date_created',
				'filterType' => GridView::FILTER_DATE,
				'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
											],
				'value'=>function($model){
					date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
					return date('d-M-Y',($model->date_created));
				}
			],


			[
				'attribute'=>'date_due',
				'filterType' => GridView::FILTER_DATE,
				'filterWidgetOptions' => [ 
												'pluginOptions' => [ 
														'format' => 'yyyy-mm-dd' 
												] 
											],
				'value'=>function($model){
					date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
					return date('d-M-Y',($model->date_created));
				}
			],

			// 'customer_id',
			
            /*[
				'attribute'=>'customer_id',
				'filterType' => GridView::FILTER_SELECT2,
				'filter'=>ArrayHelper::map(Customer::find()->all(),'id','customer_name'),
				'filterWidgetOptions' => [ 
					'options' => [ 
						'placeholder' => Yii::t('app', 'All...') 
					],
					'pluginOptions' => [ 
						'allowClear' => true 
					] 
				],
				'value'=>function($model){
					return $model->customer['customer_name'];
				}
			],*/
			
			[
				'attribute'=>'currency_id',
				'filterType' => GridView::FILTER_SELECT2,
				'width' => '150px',
				'filter'=>ArrayHelper::map(Currency::find()->orderby('currency')->all(),'id','currency'),
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
           // 'currency_id', 
          /* 'sub_total', 
		    [
				'attribute'=>'discount_type_id',
				'filterType' => GridView::FILTER_SELECT2,
				'filter'=>ArrayHelper::map(DiscountType::find()->all(),'id','discount_type'),
				'filterWidgetOptions' => [ 
					'options' => [ 
						'placeholder' => Yii::t('app', 'All...') 
					],
					'pluginOptions' => [ 
						'allowClear' => true 
					] 
				],
				'value'=>function($model){
					return $model->discountType->discount_type;
				}
			],
            //'discount_type_id', 
//            'discount_figure', 
            'discount_amount', 
            'total_tax_amount', */
            'grand_total', 
			//'invoice_status_id',
			['attribute'=>'invoice_status_id',
					'filterType' => GridView::FILTER_SELECT2,
						'format' => 'raw',
						'width' => '150px',
						'filter' => ArrayHelper::map(InvoiceStatus::find()->asArray()->all(), 'id', 'label'),
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
                    if($model->invoice_status_id == InvoiceStatus::_PAID)
						return "<span class=\"label label-primary\">" .  $model->invoiceStatus->label . "</span>";
					else if($model->invoice_status_id == InvoiceStatus::_PARTIALLYPAID)
						return "<span class=\"label label-info\">" . $model->invoiceStatus->label . "</span>";
					else if($model->invoice_status_id == InvoiceStatus::_CANCELLED)
						return "<span class=\"label label-success\">" . $model->invoiceStatus->label . "</span>";
					else if($model->invoice_status_id == InvoiceStatus::_UNPAID)
						return "<span class=\"label label-danger\">" . $model->invoiceStatus->label . "</span>";
					else
						return "<span class=\"label label-danger\">" . $model->invoiceStatus->label . "</span>";
						
				}
			],
           // 'notes',
			/*[
			'attribute'=>'notes',
			'format'=>'raw',
			],*/
//            'active', 
//            'added_at', 
//            'updated_at', 

            [
                'class' => '\kartik\grid\ActionColumn',
				'header'=>Yii::t('app','Action'),
				'template' => '{download} {view} {update} {delete}',
				'width' =>'100px',
                'buttons' => [
				
				 'download' => function ($url, $model) {
					                return Html::a('<span class="fa fa-file-pdf-o"></span>', Yii::$app->urlManager->createUrl(['invoice/invoice/download','id'=>$model->id]),
									[ 'title' => Yii::t('app', 'Download Invoice'),'data-pjax'=>"0" ]);
												  },
												  
                'update' => function ($url, $model) {
														if(Yii::$app->user->identity->userType->type=="Customer")
														{
															return '';
														}
														else
														{
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['invoice/invoice/update','id' => $model->id,'edit'=>'t']), [
																		'title' => Yii::t('app', 'Edit'),
																	  ]);
														}
													},
				 'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['invoice/invoice/view','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'View'),
                                                  ]);},
				'delete' => function($url,$model){
														if(Yii::$app->user->identity->userType->type=="Customer")
														{
															return '';
														}
														else
														{
															return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['invoice/invoice/delete','id' => $model->id]), [
														'title' => Yii::t('app', 'Delete'),
														'data' => [                          
																	'method' => 'post',                          
																	'confirm' => Yii::t('app', 'Are you sure?')],
																  ]);
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
				'before'=>Yii::$app->user->identity->userType->type=="Customer"?'':Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','Add'), ['create'], ['class' => 'btn btn-success btn-sm']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '.Yii::t('app','Reset List'), ['index'], ['class' => 'btn btn-info btn-sm']),
				'showFooter'=>false
			],
    ]); Pjax::end(); ?>

</div>
