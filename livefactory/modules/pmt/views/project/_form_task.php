<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;
use livefactory\models\Project;
use livefactory\models\TaskStatus;
use livefactory\models\TaskPriority;
use livefactory\models\TaskType;
use livefactory\models\User;
use kartik\slider\Slider;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Task $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="task-form">
    <?php
				
				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL 
				] );
				
				echo Form::widget ( [ 
						
						'model' => $taskModel,
						'form' => $form,
						'columns' => 1,
						'attributes' => [ 
								
								'task_name' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Name' ).'...',
												'maxlength' => 1024 
										] 
								] 
						] 
				] );
				echo Form::widget ( [ 
						
						'model' => $taskModel,
						'form' => $form,
						'columns' => 4,
						'attributes' => [
								
								// 'task_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Task ID...', 'maxlength'=>255]],
								
								/*'project_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter Project ID...' 
										],
										'items' => ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->asArray ()->all (), 'id', 'project_name' ) 
								],*/
								
								'task_status_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Status' ).'...' 
										],
										'items' => ArrayHelper::map ( TaskStatus::find ()->where("active=1 and id=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )   , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'
                                        ]  
								],
								
								'task_priority_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Priority' ).'...' 
										],
										'items' => ArrayHelper::map ( TaskPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )    , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Task Priority' ).'--'
                                        ] 
								],
								
								'task_type_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Type' ).'...' 
										],
										'items' => ArrayHelper::map ( TaskType::find ()->where("active=1")->asArray ()->all (), 'id', 'label' )    , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Task Type' ).'--'
                                        ] 
								],
								
								'user_assigned_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Assigned User' ).'...' 
										],
										'items' => ArrayHelper::map ( User::find ()->where("active=1")->orderBy ( 'first_name' )->asArray ()->all (), 'id',function ($user, $defaultValue) {
       								 $username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    })    , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Assigned User').'--'
                                        ] 
								],
								
								/*
								'payment_rate' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Payment Rate...' 
										] 
								],
								*/
								
								
								

								/*'progress' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Progress...' 
										] 
								],
								
								'time_spent' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter Time Spent...',
												'maxlength' => 11,
												'value'=>0 
										] 
								] */
						] 
				]
				 );
				


				
				echo Form::widget ( [ 
						
						'model' => $taskModel,
						'form' => $form,
						'columns' => 4,
						'attributes' => [ 
								

								'expected_start_datetime' => [ 
										'type' => Form::INPUT_WIDGET,
										'widgetClass' => DateControl::classname (),
										'options' => [ 
												'type' => DateControl::FORMAT_DATETIME,
												'readonly' => true,
										] 
								],
								'expected_end_datetime' => [ 
										'type' => Form::INPUT_WIDGET,
										'widgetClass' => DateControl::classname (),
										'options' => [ 
												'type' => DateControl::FORMAT_DATETIME,
												'readonly' => true, 
										] 
								],
								
								'actual_start_datetime' => [ 
										'type' => Form::INPUT_WIDGET,
										'widgetClass' => DateControl::classname (),
										'options' => [ 
												'type' => DateControl::FORMAT_DATETIME ,
												'readonly' => true,
										] 
								] ,
								
							
								'actual_end_datetime' => [ 
										'type' => Form::INPUT_WIDGET,
										'widgetClass' => DateControl::classname (),
										'options' => [ 
												'type' => DateControl::FORMAT_DATETIME ,
												'readonly' => true,
										] 
								]
						] 
				] );

				
				
				
				
				?><!--<div class="row">
             <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="name">Expected Start Date:</label>
               <div class="input-group date form_datetime1" data-date="" 
               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">
                     <span class="input-group-addon" title="Select date & time">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                    <span class="input-group-addon" title="Clear field">
                    	<span class="glyphicon glyphicon-remove"></span>
                    </span>
					<input type="text" class="form-control input-sm" name="Task[expected_start_datetime]" value="<?=date('Y-m-d H:i:s')?>"/>
               </div>
                </div>
            </div>
			 <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="lname">Expected Completion Date:</label>
                    <div class="input-group date form_datetime2" data-date="" 
               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">
                     <span class="input-group-addon" title="Select date & time">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                    <span class="input-group-addon" title="Clear field">
                    	<span class="glyphicon glyphicon-remove"></span>
                    </span>
					<input type="text" class="form-control input-sm" name="Task[expected_end_datetime]"  />
					
               </div>
                </div>
            </div>
         	 <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="name">Start Time:</label>
                    <div class="input-group date form_datetime3" data-date="" 
               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">
                     <span class="input-group-addon" title="Select date & time">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                    <span class="input-group-addon" title="Clear field">
                    	<span class="glyphicon glyphicon-remove"></span>
                    </span>
					<input type="text" class="form-control input-sm" name="Task[actual_start_datetime]" />
               </div>
                </div>
            </div>
			 <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="lname">Completion Time:</label>
                    <div class="input-group date form_datetime4" data-date="" 
               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">
                     <span class="input-group-addon" title="Select date & time">
                        <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                    <span class="input-group-addon" title="Clear field">
                    	<span class="glyphicon glyphicon-remove"></span>
                    </span>
					<input type="text" class="form-control input-sm" name="Task[actual_end_datetime]" />
               </div>
                </div>
            </div>
			</div>--><?php
				
				/*echo Form::widget ( [ 
						
						'model' => $sub_task,
						'form' => $form,
						'columns' => 1,
						'attributes' => [ 
								
								'task_description' => [ 
										'type' => Form::INPUT_TEXTAREA,
										'options' => [ 
												'placeholder' => 'Enter Task Description...',
												'rows' => 6 
										] 
								] 
						] 
				] );
				$form->field ( $sub_task, 'task_description' )->widget ( CKEditor::className (), [ 
						'options' => [ 
								'rows' => 10 
						],
						'preset' => 'basic' 
				] );*/
				echo '<div class="row">
                <div class="col-sm-12">
					<div class="form-group">
                    <label class="control-label" for="lname">'.Yii::t('app', 'Description').':
                    </label>
                    <div class="controls">
                      <textarea class="form-control input-sm ckeditor" id="task_description" name="Task[task_description]"  rows="8" style="width:100%"></textarea>
                    </div>
                </div>
				</div>
			</div>';
				echo "<input type='hidden' name='Task[project_id]' value='".$_GET['id']."'>
				<input type='hidden' name='Task[added_by_user_id]' value='".Yii::$app->user->identity->id."'>
				<input type='hidden' name='Task[added_at]' value='".strtotime(date('Y-m-d'))."'>
				<input type='hidden' name='add_task' value='true'>";
				//$form->hiddenField($task,'parent_id',array('value'=>$_GET['id']));
			echo Html::submitButton ( $taskModel->isNewRecord ? Yii::t('app','Create') : Yii::t('app', 'Update'), [ 
						'class' => $taskModel->isNewRecord ? 'btn btn-success  btn-sm project_task' : 'btn btn-primary  btn-sm' , 'id' => 'project_task_button',
				] );
				ActiveForm::end ();
				
				
				?>
</div>
