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
			[
                'attribute'=> 'resolution_number',
                'label' => Yii::t('app','Resolution Number'),
                //'width'=>'350px',
                'format'=>'raw',
                'value'=>function($model){
					return '<a href="index.php?r=support/ticket-resolution/update&id='.$model->id.'">'.$model->resolution_number.'</a>';
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
	  
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode(Yii::t('app', 'Link Resolutions')).' </h3>',
            'type'=>'info',
            'before' => '<form action="" method="post" name="frmx"><input type="hidden" name="_csrf" value="'.$this->renderDynamic('return Yii::$app->request->csrfToken;').'"> <input type="hidden" name="multiple_link_res" value="true"> <a href="javascript:void(0)" onClick="all_link()" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-link"></i> ' . Yii::t('app', "Link Selected") . '</a>',
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
