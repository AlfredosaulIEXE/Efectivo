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
use kartik\widgets\DateTimePicker;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Task $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="task-form">
    <?php
			$dFlag = false;
			$disabled_task_status_id=false;
			if(!isset($_REQUEST['id'])){
				$model->task_priority_id=\livefactory\models\DefaultValueModule::getDefaultValueId('task_priority');
				$model->task_type_id=\livefactory\models\DefaultValueModule::getDefaultValueId('task_type');
				$model->task_status_id=TaskStatus::_NEEDSACTION; //Needs action
				$disabled_task_status_id=false;
			}

			if ($model->expected_start_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->expected_start_datetime=date('Y-m-d H:i:s', $model->expected_start_datetime);
			}

			if ($model->expected_end_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->expected_end_datetime=date('Y-m-d H:i:s', $model->expected_end_datetime);
			}

			if ($model->actual_start_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->actual_start_datetime=date('Y-m-d H:i:s', $model->actual_start_datetime);
			}

			if ($model->actual_end_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->actual_end_datetime=date('Y-m-d H:i:s', $model->actual_end_datetime);
			}

				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL 
				] );
				
				echo Form::widget ( [ 
						
						'model' => $model,
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
				
				if(Yii::$app->params['user_role'] == 'admin'){
					$projects =ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->asArray ()->all (), 'id', 'project_name' ) ;	
				}else{
					$projects=ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->where(" id in(Select project_id
FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray ()->all (), 'id', 'project_name' ) ;	
				}
				if(isset($_GET['id'])){
					$users=ArrayHelper::map ( User::find ()->orderBy ( 'first_name' )->where("active=1")->asArray ()->all (), 'id', 
										function ($user, $defaultValue) {
       								 $username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    });
				}else{
					$users=ArrayHelper::map ( User::find ()->where('id=0')->orderBy ( 'first_name' )->asArray ()->all (), 'id', 
										function ($user, $defaultValue) {
       								$username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    });	
				}
				if(isset($_REQUEST['id'])){
					/*if(isset($_COOKIE['inserted_notes'.$_REQUEST['id']])){
						$model->task_progress=100;
						$model->task_status_id=2;
					}*/
					if($model->task_progress < 1){
						$model->task_progress=0;
					}
				}
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [
						'project_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										
										'items' =>$projects, 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Project' ).'--'
                                        ] 
								],
								
								'user_assigned_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter Assigned User...' 
										],
										'items' => $users, 
										'options' => [ 
                                                'prompt' => '--'.Yii::t ( 'app', 'Select User' ).'--'
                                        ] 
								],
								
								'task_status_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Status' ).' ID...' 
										],
										'items' => ArrayHelper::map ( TaskStatus::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--',
												'disabled' => $disabled_task_status_id,
                                        ] 
								],
								
								'task_priority_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Priority' ).'...' 
										],
										'items' => ArrayHelper::map ( TaskPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Task Priority' ).'--'
                                        ] 
								],
								'task_type_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Task Type' ).'...' 
										],
										'items' => ArrayHelper::map ( TaskType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t ( 'app', 'Task Type' ).'--'
                                        ] 
								],
								
								
								
						'task_progress' => [ 
										'type' => Form::INPUT_TEXT,
										'value'=>$model->task_progress?$model->task_progress:0,
										'options' => [ 
												'placeholder' => 'Enter Progress...' ,
												'value'=>$model->task_progress?$model->task_progress:0
										] 
								],
								
								'time_spent' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t ( 'app', 'Time Spent' ).'...',
												'maxlength' => 11,
												'value'=>$model->time_spent?$model->time_spent:0,
												'readonly'=>'readonly'
										] 
								] 
						] 
				]
				 );
				
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [ 
								
								'expected_start_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateTimePicker::classname(),
										'options'=>[
											'type'=>DateTimePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd hh:i:s',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
								],
								'expected_end_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateTimePicker::classname(),
										'options'=>[
											'type'=>DateTimePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd hh:i:s',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
								],
								
								'actual_start_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateTimePicker::classname(),
										'options'=>[
											'type'=>DateTimePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd hh:i:s',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
								],

								'actual_end_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateTimePicker::classname(),
										'options'=>[
											'type'=>DateTimePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd hh:i:s',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
									]
						] 
				] );
				
				
				if(!isset($_REQUEST['id'])){
					$model->task_progress=0;
				}
				$form->field ( $model, 'task_progress' )->widget ( Slider::classname (), [
						'value'=>0,
						
						'sliderColor' => Slider::TYPE_SUCCESS,
						'handleColor' => Slider::TYPE_SUCCESS,
						'pluginOptions' => [ 
								'handle' => 'square',
								'min' => 0,
								'max' => 100,
								'step' => 10,
								
								'tooltip' => 'always' 
						] 
				] );
				if(!isset($_REQUEST['id'])){
				echo Form::widget ( [ 
						
						'model' => $model,
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
				$form->field ( $model, 'task_description' )->widget ( CKEditor::className (), [ 
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
				
				echo '<div class="row">
                <div class="col-sm-12">
					<div class="form-group">
                    <label class="control-label" for="lname">'.Yii::t('app','Notes').':
                    </label>
                    <div class="controls">
                      <textarea class="form-control input-sm ckeditor" name="notes" id="notes" rows="8" style="width:100%"></textarea>
                    </div>
                </div>
				</div>
			</div>';
			echo '<input type="hidden" name="Task[task_status_id]" value="'.$model->task_status_id.'">';

			if($model->isNewRecord)
			{
			?>
				<input type="hidden" name="Task[added_by_user_id]" value="<?=Yii::$app->user->identity->id?>">
			<?php
			}

			echo Html::submitButton ( $model->isNewRecord ?Yii::t('app','Create')  :Yii::t('app','Update') , [ 
						'class' => $model->isNewRecord ? 'btn btn-success update_task btn-sm' : 'btn btn-primary update_task btn-sm' 
				] );
				
				}
				if(isset($_GET['id'])){
				
			echo "<input type='hidden' name='Task[project_id]' value='".$model->project_id."'>";	
				}
				
ActiveForm::end ();
				?>
</div>
