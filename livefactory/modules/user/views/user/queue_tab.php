<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Queue $searchModel
 */

//$this->title = Yii::t('app', 'Queues');
//$this->params['breadcrumbs'][] = $this->title;
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
<div class="queue-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
        'columns' => [
           // ['class' => '\kartik\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn','header'=>''],

           // 'id',
            //'ticket_title',
			[ 

					'attribute' => 'queue_title',
	
					'width' => '450px' ,
	
					'format' => 'raw',
	
					'value' => function ($model, $key, $index, $widget)
	
					{
	
						return '<a href="index.php?r=liveobjects/queue/update&id='.$model->id.'">'.$model->queue_title.'</a>';
	
					}
	
			],
			[ 

				'attribute' => 'queue_supervisor_user_id',
				
				//'label' => Yii::t('app','User'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '300px',
				

				'value' => function ($model, $key, $index, $widget)

				{

					 //var_dump($model->queue_supervisor_user_id);
					 

					if (isset($model->queueSupervisorUser) && ! empty($model->queueSupervisorUser->first_name)) {
                            $username = $model->queueSupervisorUser->username ? $model->queueSupervisorUser->username : $model->queueSupervisorUser->email;
                            if (Yii::$app->params['USER_IMAGE'] == 'Yes') {
                                $users = '<div class="project-people">';
                                $path = '../users/' . $model->queueSupervisorUser->id . '.png';
                                if (file_exists($path)) {
                                    $image = '<img  class="img-circle"  src="' . $path . '"  data-toggle="hover" data-placement="top" data-content="' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')">';
                                } else {
                                    $image = '<img   class="img-circle" src="../users/nophoto.jpg"  data-toggle="hover" data-placement="top" data-content="' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')">';
                                }
                                $users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->queueSupervisorUser->id . '\')">' . $image . '</a>';
                                
                                $users .= '</div>';
                                return $users;
                            } else {
                                $users = ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->queueSupervisorUser->id . '\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">' . $model->queueSupervisorUser->first_name . ' ' . $model->queueSupervisorUser->last_name . ' (' . $username . ')</a>';
                                return $users;
                            }
                        }

				} 

		],
		/*[ 

				'attribute' => 'queue_owner_user_id',

				//'label' => Yii::t('app','User'),

				'filterType' => GridView::FILTER_SELECT2,

				'format' => 'raw',

				'width' => '300px',

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

					if (isset ( $model->user1 ) && ! empty ( $model->user1->first_name )){
						$username=$model->user1->username?$model->user1->username:$model->user1->email;
						return $model->user1->first_name.' '.$model->user1->last_name.' ('.$username.')';
					}

				} 

		],*/
		[ 
				'attribute' => 'active',
			//	'label' => 'Active',
				'format' => 'raw',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => $status,
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
						return statusLabel ( $model->active );
				} 
		],
            //'queue_title',
           // 'queue_supervisor_user_id',
            //'queue_owner_user_id',
           // 'active',

           /* [
                'class' => '\kartik\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['liveobjects/queue/update','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);},
												  'view' => function($url,$model){

												return '';

											

											}


                ],
            ],*/
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
		'toolbar'=>false,



        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Queues').' </h3>',
            'type'=>'info',
            'before'=>'',            'after'=>'',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>