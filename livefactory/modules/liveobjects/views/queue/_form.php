<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\User;
use livefactory\models\Department;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Queue $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="queue-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 2,
    'attributes' => [
					
					'department_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'items' => ArrayHelper::map (Department::find ()->where("active=1")->orderBy ( 'name' )->asArray ()->all (), 'id','name'), 

										'options' => [ 

                                                'prompt' => '--'.Yii::t ( 'app', 'Department' ).'--'

                                        ] 



								],

					'queue_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Queue Title...', 'maxlength'=>255]], 
							'queue_supervisor_user_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'items' => ArrayHelper::map ( User::find ()->where("active=1")->orderBy ( 'first_name' )->asArray ()->all (), 'id', 
										function ($user, $defaultValue) {
       								 $username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    }), 

										'options' => [ 

                                                'prompt' => '--'.Yii::t ( 'app', 'Supervisor User' ).'--'

                                        ] 



								],
								
								
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

								]


    ]);
	
    
	if(!isset($_GET['id'])){
		echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
		
	}
    ActiveForm::end(); ?>

</div>
