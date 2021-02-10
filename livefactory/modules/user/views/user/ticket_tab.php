<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;

use livefactory\models\TicketType;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;
use livefactory\models\TicketStatus;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Ticket $searchModel
 */
function statusLabel3($status)
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
?>
<div class="ticket-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
		'pjax' => true,
        'columns' => [
			//['class' => '\kartik\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],

           [ 

					'attribute' => 'ticket_id',
	
					'format' => 'raw',
	
					'value' => function ($model, $key, $index, $widget)
	
					{
	
						return '<a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>';
	
					}
	
			],
			[ 

					'attribute' => 'ticket_title',
	
					'width' => '250px' ,
	
					'format' => 'raw',
	
					
	
			],
			[ 

					'attribute' => 'ticket_description',
	
					'width' => '350px' ,
	
					'format' => 'raw',
	
			],
			[ 

				'attribute' => 'user_assigned_id',

				'label' => Yii::t('app','User'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '200px',

				'filter' => ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->where("active=1")->asArray ()->all (), 'id',
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

					// var_dump($model->user);

					if (isset ( $model->user ) && ! empty ( $model->user->first_name )){
						$username=$model->user->username?$model->user->username:$model->user->email;
						return $model->user->first_name.' '.$model->user->last_name.' ('.$username.')';
					}

				} 

		],
		[ 

			'attribute' => 'ticket_status_id',

			'label' => Yii::t('app','Status'),

			'filterType' => GridView::FILTER_SELECT2,

			'format' => 'raw',

			'width' => '150px',

			'filter' => ArrayHelper::map (TicketStatus::find ()->where("active=1")->orderBy ('sort_order' )->asArray ()->all (), 'id', 'label' ),

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

				// var_dump($model->ticketPriority);

				if (isset ( $model->ticketStatus ) && ! empty ( $model->ticketStatus->label ))

					return statusLabel3 ( $model->ticketStatus->label );

			} 

	],

	[ 

			'attribute' => 'ticket_priority_id',

			'label' => Yii::t('app','Priority'),

			'filterType' => GridView::FILTER_SELECT2,

			'format' => 'raw',

			'width' => '150px',

			'filter' => ArrayHelper::map ( TicketPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),

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

				// var_dump($model->ticketPriority);

				if (isset ( $model->ticketPriority ) && ! empty ( $model->ticketPriority->label ))

					return statusLabel3 ( $model->ticketPriority->label );

			} 

	],
            //'ticket_description:ntext',
           // 'ticket_type_id',
           // 'ticket_priority_id',
//            'ticket_impact_id', 
//            'queue_id', 
//            'assigned_user_id', 
//            'referenced_ticket_id', 
//            'ticket_status_id', 
//            'escalated_flag', 
//            'added_at', 
//            'updated_at', 
//            'created_by', 
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Yii::t('app', 'Tickets').' </h3>',
            'type'=>'info',
            'before'=>'',            'after'=>'',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>