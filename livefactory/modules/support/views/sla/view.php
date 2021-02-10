<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;

use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Sla $model
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Tickets'), 'url' => ['/support/ticket/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Ticket Sla'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-sla-view">
    


    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title = yii::t('app','Ticket Sla'),
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            //'id',
            ['attribute'=>'ticket_priority_id',
										'value' => $model->ticketPriority->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TicketPriority::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]],
			['attribute' => 'ticket_impact_id',
										'value' => $model->ticketImpact->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TicketImpact::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]],
			
				'sla',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>
