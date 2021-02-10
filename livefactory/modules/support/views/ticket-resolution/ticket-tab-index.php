<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\ResolutionReference;
use livefactory\models\TicketResolution;
use livefactory\models\search\TicketResolution as TicketResolutionSearch

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\TicketResolution $searchModel
 */

//$this->title = Yii::t('app', 'Ticket Resolutions');
//$this->params['breadcrumbs'][] = $this->title;
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
        //'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
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
					return '<a href="index.php?r=support/ticket-resolution/ticket-tab-view&id='.$model->id.'">'.$model->resolution_number.'</a>';
                   // return $model->ticketTitle->ticket_id;
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


            [
                'class' => '\kartik\grid\ActionColumn',
                'header'=>'Actions',
				'template' => '{view} {delete}',
                'buttons' => [
                'view' => function ($url, $model) 
						  {
                              return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['support/ticket-resolution/ticket-tab-view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('app', 'View'),
                                                  ]);
							//return '<a class="glyphicon glyphicon-eye-open" href="javascript:void(0)" onClick="view_res()"></a>';
						  },
				'delete' => function ($url, $model) 
						  {
                              /*return Html::a('<span class="glyphicon glyphicon-remove"></span>', Yii::$app->urlManager->createUrl(['support/ticket/update','id' => $_REQUEST['id'],'unlink'=>$model->id]), [
                                                    'title' => Yii::t('app', 'Unlink'),
                                                  ]);*/

							 return '<a href="index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&unlink='.$model->id.'" onClick="return confirm(\'Are you Sure!\')" title="Unlink"><span class="glyphicon glyphicon-remove"></span></a>';

						  },
                             ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode('Resolutions').' </h3>',
            'type'=>'info',
            //'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add New Resolution', ['create'], ['class' => 'btn btn-success']),
			'before'=>'<a class="btn btn-success btn-sm" href="javascript:void(0)" onClick="add_res()">

							<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app','New Resolution').'

						</a>
						<a class="btn btn-success btn-sm" href="javascript:void(0)" onClick="link_existing()">

							<i class="glyphicon glyphicon-link"></i> '.Yii::t('app','Link With Existing Resolution').'

						</a>',
			//'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>

<script>
function add_res()
{
	$('#myresolution').modal('show');
}

function link_existing()
{
	$('.linkwithexisting').modal('show');
}
</script>

<div class="modal linkwithexisting">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=Yii::t('app', 'Link Resolutions')?></h4>
		  </div>

		  <div class="modal-body">
				 <?= $this->render('../ticket/link-resolutions', [
												'model' => $model,
												'dataProvider' => (new TicketResolutionSearch)->searchNotAddedResolutions($_REQUEST['id']),
											]) 
				?>
		  </div>

		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->