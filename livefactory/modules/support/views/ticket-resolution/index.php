<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\ResolutionReference;
use livefactory\models\User;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\TicketResolution $searchModel
 */

$this->title = Yii::t('app', 'Manage Resolutions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-resolution-index">
    <!--
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Ticket Resolution',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php 
	Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
		'pjax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],  
			//'resolution_number',
			[
                'attribute'=> 'resolution_number',
                'label' => Yii::t('app','Resolution Number'),
                //'width'=>'350px',
                'format'=>'raw',
                'value'=>function($model){
					return '<a href="index.php?r=support/ticket-resolution/update&id='.$model->id.'">'.$model->resolution_number.'</a>';
                }
            ],
            
            'subject',
            //'resolution',
			[
                'attribute'=> 'resolution',
                'label' => Yii::t('app','Resolution'),                
                'format'=>'raw',
                'value'=>function($model){
                    return $model->resolution;
                }
            ],
           /* [
                'attribute'=> 'queue_id',
                'label' => Yii::t('app','Queue Id'),                
                'format'=>'raw',
                'value'=>function($model){
                    return $model->queueTitle->queue_title;
                }
            ],*/
			/*[
				'attribute' => 'resolved_by_user_id',
				'label' => Yii::t('app','Creator'),                
                'format'=>'raw',
                'value'=>function($model){
                    return $model->user->username.' ('.$model->user->first_name.' '.$model->user->last_name.')';
			}
										
			],*/

			[ 
				'attribute' => 'resolved_by_user_id',
				'label' => Yii::t('app','Created By'),
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
				'label' => Yii::t('app','Linked Tickets'),
				'value'=>function($model){
                    return (new ResolutionReference)->getCountLinkedWithResolution($model->id);
                }
			],
			
//            ['attribute'=>'added_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 

            [
                'class' => '\kartik\grid\ActionColumn',
                'header'=>Yii::t('app', 'Actions'),
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['support/ticket-resolution/update','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);},
                                    'view' => function($url,$model){

                                                return '';

                                            

                                            },
				'delete' => function ($url, $model)
							{
								$csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');
								if ((new ResolutionReference)->getCountLinkedWithResolution($model->id) == 0)
								{
										return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['support/ticket-resolution/delete','id' => $model->id]), [
													'title' => Yii::t('app', 'Delete'),
													'data' => [                          
																'method' => 'post',                          
																'confirm' => Yii::t('app', 'Are you sure?')],
															  ]);
								}
								else
								{
									return '';
								}
							},

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
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add New Resolution', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i>'.Yii::t('app', 'Reset List'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
