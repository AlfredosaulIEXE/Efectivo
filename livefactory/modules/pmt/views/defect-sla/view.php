<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\DefectPriority;
use livefactory\models\DefectType;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var livefactory\models\DefectSla $model
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Defects'), 'url' => ['/pmt/defect/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manage Defect Sla'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="defect-sla-view">
    

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title = Yii::t('app', 'Defect Sla'),
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
           // 'id',
            ['attribute'=>'defect_priority_id',
										'value' => $model->defectPriority->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( DefectPriority::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]],
			['attribute' => 'defect_type_id',
										'value' => $model->defectType->label,
										'type' => DetailView::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( DefectType::find ()->andwhere("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
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
