<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\Status;

/**
 *
 * @var yii\web\View $this
 * @var common\models\UserType $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-type-form">

    <?php
				
				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL 
				] );
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 1,
						'attributes' => [ 
								
								'type' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Type...',
												'maxlength' => 255 
										] 
								],
								
								'label' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Label...',
												'maxlength' => 255 
										] 
								],
								
							/*	'sort_order' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Label...',
												'maxlength' => 255 
										] 
								],*/
								
								'active' => [ 
										
										'type' => Form::INPUT_DROPDOWN_LIST,
										
										'options' => [ 
												
												'placeholder' => 'Enter Status ...' 
										],
										
										'items' =>array('0'=>Yii::t('app', 'No'),'1'=>Yii::t('app', 'Yes'))  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select').'--'
                                        ]
								] 
						]
						 
				] );
				echo Html::submitButton ( $model->isNewRecord ? 'Create' : 'Update', [ 
						'class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm' 
				] );
				ActiveForm::end ();
				?>

</div>