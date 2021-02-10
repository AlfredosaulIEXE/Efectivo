<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\ProjectSource $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="project-source-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'source' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Source...', 'maxlength' => 255]],

            'label' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Label...', 'maxlength' => 255]],

       //     'active' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Active...']],
'active' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'Active',
										'options' => [ 
												'placeholder' => 'Enter State ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select').'--'
                                        ]
								], 

      //      'sort_order' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Sort Order...']],

      //      'added_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Added At...']],

     //       'updated_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Updated At...']],

        ]

    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    );
    ActiveForm::end(); ?>

</div>
