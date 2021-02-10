<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use dosamigos\ckeditor\CKEditor;
//use livefactory\models\TicketType;
use livefactory\models\TicketPriority;
use livefactory\models\TicketImpact;
use livefactory\models\TicketStatus;
use livefactory\models\TicketCategory;
use livefactory\models\User;
use livefactory\models\Queue;
use livefactory\models\Customer;
use livefactory\models\Department;
use livefactory\models\TicketCategory1;
use livefactory\models\UserType;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Ticket $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="ticket-form">

    <?php

	if ($model->due_date != '')
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$model->due_date=date('Y/m/d H:i:s', $model->due_date);	// H for 24 hrs format. Use h for 12 hrs format
	}

	if(!isset($_REQUEST['id'])){
		
		$model->ticket_priority_id=\livefactory\models\DefaultValueModule::getDefaultValueId('ticket_priority');
		$model->ticket_type_id=0;
		$model->ticket_status_id=\livefactory\models\DefaultValueModule::getDefaultValueId('ticket_status');
		$model->ticket_impact_id=\livefactory\models\DefaultValueModule::getDefaultValueId('ticket_impact');
		$model->ticket_category_id_1=\livefactory\models\DefaultValueModule::getDefaultValueId('ticket_category');
	}
	
	$dFlag = false;
	if(!empty($_GET['customer_id']))
	{
		$model->ticket_customer_id=$_GET['customer_id'];
		$dFlag =true;
	}
	// find user type
	if(Yii::$app->user->identity->entity_type == 'customer')
	{
		$model->ticket_customer_id = Yii::$app->user->identity->entity_id;
		$dFlag =true;
	}

	$queues = ArrayHelper::map ( Queue::find ()->where("id=0")->orderBy ( 'queue_title' )->asArray ()->all (), 'id', 'queue_title' ) ;	
	$users=ArrayHelper::map ( User::find ()->where('id=0')->orderBy ( 'first_name' )->asArray ()->all (), 'id', 
								function ($user, $defaultValue) 
								{
									$username=$user['username']?$user['username']:$user['email'];
									return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
								}
							);	
	$category1 = ArrayHelper::map ( TicketCategory::find ()->where("id=0")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ); 
	$category2 = ArrayHelper::map ( TicketCategory::find ()->where("id=0")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' );

	$form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL ]); 
	echo Form::widget ( [ 
	'model' => $model,
    'form' => $form,
    'columns' => 4,
    'attributes' => [
		'ticket_title'=>[
					'type'=> Form::INPUT_TEXT, 
					'options'=>[
							'placeholder'=>Yii::t ( 'app', 'Enter Subject...'), 
							'maxlength'=>255] ,
										'columnOptions' => [ 
												'colspan' => 3
										]], 

							'ticket_customer_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Customer').' ID...' 
										],
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(Customer::find()->orderBy('customer_name')->asArray()->all(), 'id', 'customer_name')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Customer').'--',
												'disabled' => $dFlag,
                                        ] 
								]
					]
					]);

	echo Form::widget ( [ 
	'model' => $model,
    'form' => $form,
    'columns' => 4,
    'attributes' => [
							

								'ticket_status_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TicketStatus::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ] 
								],

								'ticket_priority_id' => [ 
									'type' => Form::INPUT_DROPDOWN_LIST,
									'items' => ArrayHelper::map ( TicketPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 

										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Priority' ).'--'
                                        ] 
								],
						
								'ticket_impact_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' => ArrayHelper::map ( TicketImpact::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Impact' ).'--'
                                        ] 
								],
									
								'department_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items'=>ArrayHelper::map(Department::find()->orderBy('name')->asArray()->all(), 'id', 'name')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Department').'--'
                                        ] 
								],

								
						]
					]);

	echo Form::widget([
    'model' => $model,
    'form' => $form,
    'columns' => 4,
    'attributes' => [

	
						'queue_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' =>$queues ,  
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Queue' ).'--'
                                        ] 
								],


								'user_assigned_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' =>$users, 
										'options' => [ 
                                                'prompt' => '--'.Yii::t ( 'app', 'Select User' ).'--'
                                        ] 
								], 


						'ticket_category_id_1' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'items' => $category1, 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Category 1' ).'--'
                                        ] 
								],
    					'ticket_category_id_2' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Category 2').' ID...' 
										],
										'items' => $category2, 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Category 2').'--'
                                        ] 
								],
					

					//'escalated_flag'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t ( 'app', 'Enter Escalated Flag...'), 'maxlength'=>255]], 

						'due_date' => [ 
										'type' => Form::INPUT_WIDGET,
										'widgetClass' => DateControl::classname (),
										'options' => [ 
												'language' => 'eg',
												'type' => DateControl::FORMAT_DATETIME,
												'disabled' => true,
										] 
								],
						'time_spent' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
											'disabled' => true,
										]
								],
			]
    ]);
	if(!isset($_REQUEST['id'])){
		echo Form::widget ( [ 
		'model' => $model,
		'form' => $form,
		'columns' => 1,
		'attributes' => [
			'ticket_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Description...','rows'=> 6]], 
			]
			]);
		$form->field ( $model, 'ticket_description' )->widget ( CKEditor::className (), [ 
						'options' => [ 
								'rows' => 10 
						],
						'preset' => 'custom',
						'clientOptions' => [
						'height' => 200,
						'toolbarGroups' => [
						['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
						['name' => 'editing', 'groups' => [ 'find', 'selection', 'spellchecker']],
						['name'=>'links','groups'=>['links']],
						['name'=>'insert','groups'=>['insert']],
						['name' => 'tools'],
						['name'=>'document','groups'=>['mode', 'document', 'doctools']],
						'/',
						['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
						['name' => 'paragraph', 'groups' => ['templates', 'list']],
						['name'=>'paragraph','groups'=>['list', 'indent', 'blocks']],
						['name'=>'styles','groups'=>['styles']],
						['name'=>'about','groups'=>['about']]
				
				],
				
			]
				] );
	
			echo Html::submitButton ( $model->isNewRecord ?Yii::t('app','Create')  :Yii::t('app','Update') , [ 
						'class' => $model->isNewRecord ? 'btn btn-success update_ticket' : 'btn btn-primary update_ticket' 
				] );
				}
				ActiveForm::end ();
	?>
</div>