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
//$this->title = Yii::t('app', 'Tickets');
//$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <form action="" method="post" name="frm"> -->
    <?php Yii::$app->request->enableCsrfValidation = true; ?>
   
<div class="ticket-index">
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
		'pjax' => true,
        'columns' => [
			['class' => '\kartik\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
           'ticket_id',
            //'ticket_title',
			[ 
					'attribute' => 'ticket_title',
					'width' => '350px' ,
					'format' => 'raw',
					'value' => function ($model, $key, $index, $widget)
					{
						return '<a href="index.php?r=support/ticket/update&id='.$model->id.'">'.$model->ticket_title.'</a>';
					}
	
			],
			/*
			[ 
					'attribute' => 'ticket_description',
	
					'width' => '350px' ,
	
					'format' => 'raw',
	
			],
			*/
			/*[ 
				'attribute' => 'user_assigned_id',
				'label' => Yii::t('app','Assigned User'),
				'filterType' => GridView::FILTER_SELECT2,
				'format' => 'raw',
				//'width' => '200px',
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
								
							}
					}
					else
					{
						if(!empty($_GET['Ticket']['queue_id']))
						{
							return '<span class="label label-danger">Not Assigned</span>';
						}	
					}					
				} 
		],
		[ 
			'attribute' => 'ticket_category_id_1',
			'label' => Yii::t('app','Category'),
			'filterType' => GridView::FILTER_SELECT2,
			'format' => 'raw',
			//'width' => '150px',
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
	],*/
	[ 
			'attribute' => 'ticket_status_id',
			'label' => Yii::t('app','Status'),
			'filterType' => GridView::FILTER_SELECT2,
			'format' => 'raw',
			//'width' => '150px',
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
	
	/*[ 
			'attribute' => 'ticket_priority_id',
			'label' => Yii::t('app','Priority'),
			'filterType' => GridView::FILTER_SELECT2,
			'format' => 'raw',
			//'width' => '150px',
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
				
				],*/
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
            /*[
                'class' => '\kartik\grid\ActionColumn',
				'header'=>'Actions',
				'template'=>'{update}{delete}{status}',	
                'buttons' => [
                'update' => function ($url, $model) {
                                    return '';
									},
				'view' => function($url,$model){
												return '';
											
											},	
				'delete' => function($url,$model){
												return '';
											
											},	
				'status'=>function ($url, $model) {
												return '';
                                		},
                                    		
                                    		
                ],                
            ],  */          
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode(Yii::t('app', 'Link Tickets')).' </h3>',
            'type'=>'info',
            'before' => '<form action="" method="post" name="frmx"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_link" value="true"> <a href="javascript:void(0)" onClick="all_link()" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-link"></i> ' . Yii::t('app', "Link Selected") . '</a>',
            'after' => '</form>',
            'showFooter' => false
        ],
    ]); Pjax::end(); ?>
</div>
<!-- </form> -->
<script>
	function all_link(){
		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");
		if (r == true) {
			document.frmx.submit()
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
</div>
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
