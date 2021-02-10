<?php

use yii\helpers\Html;

use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;

use kartik\datecontrol\DateControl;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model livefactory\models\Glocalization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="glocalization-form">

    <?php 
				$form = ActiveForm::begin ( [ 

						'type' => ActiveForm::TYPE_VERTICAL 

				] );

				

				echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 1,

						'attributes' => [

								

								// 'parent_project_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Parent Project ID...']],

								'language' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => 'Enter Language Name...',

												'maxlength' => 255 

										],

										'columnOptions'=>['colspan'=>2], 

								] 

						]

						 

				]

				 );
				?>
    <?php
	echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 1,

						'attributes' => [ 

								

								'locale' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => 'Enter Locale...',

												'rows' => 10

										] 

								] 

						] 

				] );

				

				
	?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
