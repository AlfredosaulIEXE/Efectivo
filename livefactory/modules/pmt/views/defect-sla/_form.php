<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\DefectPriority;
use livefactory\models\DefectType;
use yii\helpers\ArrayHelper;


/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketSla $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="defect-sla-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

			'defect_priority_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( DefectPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ] 
								],

			'defect_type_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( DefectType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ] 
								],

            'start_sla' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Num Of Hours...']],

			'end_sla' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Num Of Hours...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
