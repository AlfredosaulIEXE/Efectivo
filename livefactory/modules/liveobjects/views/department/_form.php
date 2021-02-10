<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Department $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="department-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Name...', 'maxlength'=>255]], 
//'sort_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Sort Order...')]], 
////'label'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Label...', 'maxlength'=>255]], 

'active' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										//'label' => 'Active',

										'options' => [ 

												'placeholder' => Yii::t('app', 'Enter State').' ...' 

										] ,

										'columnOptions'=>['colspan'=>1],

										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t('app', 'Select Status').'--'

                                        ]

								], 
'description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Description...','rows'=> 6]], 

//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

///'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
	$form->field ( $model, 'description' )->widget ( CKEditor::className (), [ 

						'options' => [ 

								'rows' => 10 

						],

						'preset' => 'basic' 

				] );
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-sm btn-success' : 'btn btn-sm btn-primary']);
    ActiveForm::end(); ?>

</div>
