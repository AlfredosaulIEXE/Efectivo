<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;

use livefactory\models\TicketType;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;
use livefactory\models\TicketStatus;
use livefactory\models\TicketCategory;
use livefactory\models\Queue;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Ticket $searchModel
 */

$this->title = Yii::t('app', 'Pending Tickets');
$this->params['breadcrumbs'][] = $this->title;
function statusLabel($status)

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
<!--<form action="" method="post" name="frm"> -->
    <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <!--<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="multiple_del" value="true"> -->
<div class="ticket-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
		'pjax' => true,
        'columns' => [
		['class' => 'yii\grid\SerialColumn'],
			['class' => '\kartik\grid\CheckboxColumn'],
            

			 [ 

					'attribute' => 'ticket_id',
	
					'width' => '15%' ,
	
					'format' => 'raw',
	
					'value' => function ($model, $key, $index, $widget)
	
					{
	
						return '<a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_id.'</a>';
	
					}
	
			],
			
            //'ticket_title',
			[ 

					'attribute' => 'ticket_title',
	
					'width' => '30%' ,
	
					'format' => 'raw',
	
					'value' => function ($model, $key, $index, $widget)
	
					{
	
						return '<a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_title.'</a>';
	
					}
	
			],
			
			[ 

				'attribute' => 'user_assigned_id',

				'label' => Yii::t('app','Assigned'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '20%',

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
						if(Yii::$app->params['USER_IMAGE'] =='Yes'){
							$users='<div class="project-people">';
									$path='../users/'.$model->user->id.'.png';
									if(file_exists($path)){
										$image='<img  class="img-circle"  src="'.$path.'"  data-toggle="hover" data-placement="top" data-content="'.$model->user->first_name.' '.$model->user->last_name.' ('.$model->user->username.')">';								
									 }else{ 
										$image='<img   class="img-circle" src="../users/nophoto.jpg"  data-toggle="hover" data-placement="top" data-content="'.$model->user->first_name.' '.$model->user->last_name.' ('.$model->user->username.')">';
									 }
									$users.=' <a  href="javascript:void(0)" onClick="showPopup(\''.$model->user->id.'\')">'.$image.'</a>';	
						
								$users.='</div>';
								return $users;
							}else{
								$users=' <a  href="javascript:void(0)" onClick="showPopup(\''.$model->user->id.'\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">'.$model->user->first_name.' '.$model->user->last_name.' ('.$username.')</a>';
								return $users;
							}
					}

				} 

		],
		[ 

			'attribute' => 'ticket_category_id_1',

			'label' => Yii::t('app','Category'),

			'filterType' => GridView::FILTER_SELECT2,

			'format' => 'raw',

			'width' => '150px',

			'filter' => ArrayHelper::map (TicketCategory::find ()->where("active=1")->orderBy ('sort_order' )->asArray ()->all (), 'id', 'label' ),

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

				if (isset ( $model->ticketCategory ) && ! empty ( $model->ticketCategory->label ))

					return statusLabel ( $model->ticketCategory->label );

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

					return statusLabel ( $model->ticketStatus->label );

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

					return statusLabel ( $model->ticketPriority->label );

			} 

	],


	// queue name column added by deepak
			[ 

				'attribute' => 'queue_id',

				'label' => Yii::t('app','Queue Name'),
				
				'filterType' => GridView::FILTER_SELECT2,

			'format' => 'raw',
				
				'filter' => ArrayHelper::map ( Queue::find ()->where("active=1")->orderBy ( 'id' )->asArray ()->all (), 'id', 'queue_title' ),

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


				if (isset ( $model->queueName ) && ! empty ( $model->queueName->queue_title ))

					return statusLabel ( $model->queueName->queue_title );

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

            [
                'class' => '\kartik\grid\ActionColumn',
				'header'=>'Actions',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['support/ticket/update','id' => $model->id]), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);},

											'view' => function($url,$model){

												return '';

											

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
			'showFooter'=>false
        ]
    ]); Pjax::end(); ?>

</div>
<!-- </form>  -->

<script>

	function all_del(){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			document.frm.submit()

		} else {

			

		}	

	}

</script>
<script>
	function showPopup(id){
		//alert('index.php?r=liveobjects/queue/ajax-user-detail&id='+id);
		$.post('index.php?r=liveobjects/queue/ajax-user-detail&id='+id,function(r){
			$('.modal-body').html(r);
		}).done(function(){
			$('.bs-example-modal-lg').modal('show');
		})
	}
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
    </div>
      <div class="modal-body">
      
      </div>
    </div>
  </div>
</div>