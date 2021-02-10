<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;
/**
 * @var yii\web\View $this
 * @var livefactory\models\CronJobs $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cron-jobs-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'cron_job_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Cron Job Name...'), 'maxlength'=>255]], 

'cron_job_path'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Cron Job Path...'), 'maxlength'=>255]], 

'cron_job_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>Yii::t('app', 'Enter Cron Job Description...'), 'maxlength'=>255]], 

    ]


    ]);
	$form->field ( $model, 'cron_job_description' )->widget ( CKEditor::className (), [ 



						'options' => [ 



								'rows' => 10 



						],



						'preset' => 'basic' 



				] );
				    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
    ActiveForm::end(); ?>

</div>
