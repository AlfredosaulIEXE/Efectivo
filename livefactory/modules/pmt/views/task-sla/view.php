<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\TaskPriority;
use livefactory\models\TaskType;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TaskSla $model
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Tasks'), 'url' => ['/pmt/task/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Task Sla'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-sla-view">
    


    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title = yii::t('app','Task Sla'),
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            //'id',
            ['attribute'=>'task_priority_id',
										'value' => $model->taskPriority->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TaskPriority::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]],
			['attribute' => 'task_type_id',
										'value' => $model->taskType->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TaskType::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]],
			
			'start_sla',
            'end_sla',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
        ],
        'enableEditMode' => true,
    ]) ?>

</div>
