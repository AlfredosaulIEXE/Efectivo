<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;


use dosamigos\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var livefactory\models\EmailTemplate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'template_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> Yii::t('app', 'Enter Template Name').'...', 'maxlength'=>255]], 

'template_subject'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> Yii::t('app', 'Enter Template Subject').'...','rows'=> 6]], 

//'template_body'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Template Body...','rows'=> 6]], 

//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
	echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 1,

						'attributes' => [ 

								

								'template_body' => [ 

										'type' => Form::INPUT_TEXTAREA,

										'options' => [ 

												'placeholder' => Yii::t('app', 'Body').'...',

												'rows' => 6 

										] 

								] 

						] 

				] );
	$form->field ( $model, 'template_body' )->widget ( CKEditor::className (), [ 

						'options' => [ 

								'rows' => 10 

						],

						'preset' => 'basic' 

				] );
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
    ActiveForm::end(); ?>

</div>
