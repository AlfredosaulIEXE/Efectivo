<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketResolution $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ticket-resolution-form">
<?php

$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
    if(empty($_GET['id']))
	{ 
    echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 4,
    'attributes' => [
    		'subject'=>[
					'type'=> Form::INPUT_TEXT, 
					'options'=>[
							'placeholder'=>Yii::t ( 'app', 'Enter Subject...'), 
							'maxlength'=>255] ,
										'columnOptions' => [ 
											'colspan' => 4
										]]
    ]
    ]);
    echo Form::widget ( [ 
		'model' => $model,
		'form' => $form,
		'columns' => 4,
		'attributes' => [
			'resolution'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Resolution...','rows'=> 6]], 
			]
			]);
		$form->field ( $model, 'resolution' )->widget ( CKEditor::className (), [ 
						'options' => [ 

								'rows' => 10 
						],
						'preset' => 'basic' 
	]);
	
	
		
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    
    }
	
	ActiveForm::end();
    ?>
   
</div>
