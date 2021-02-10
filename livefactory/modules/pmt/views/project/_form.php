<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dosamigos\ckeditor\CKEditor;
use livefactory\models\ProjectType;
use livefactory\models\User;
use livefactory\models\Currency;
use livefactory\models\Customer;
use livefactory\models\ProjectStatus;
use livefactory\models\ProjectPriority;
use livefactory\models\ProjectSource;
use kartik\slider\Slider;
use kartik\widgets\DateTimePicker;
use kartik\date\DatePicker;
/**
 *
 * @var yii\web\View $this
 * @var common\models\Project $model
 * @var yii\widgets\ActiveForm $form
 */
 
?>

<div class="project-form">

    <?php
	$disabled_project_status_id=false;
	$disabled_expected_start_date=true;
	$disabled_expected_end_date=true;
	$disabled_actual_start_date=true;
	$disabled_actual_end_date=true;

			if(isset($_REQUEST['id'])){
				if($model->project_progress < 1){
					$model->project_progress=0;
				}
			}else{
				$model->project_priority_id=\livefactory\models\DefaultValueModule::getDefaultValueId('project_priority');
				$model->project_type_id=\livefactory\models\DefaultValueModule::getDefaultValueId('project_type');
				// new status id will always be needs action
				//$model->project_status_id=\livefactory\models\DefaultValueModule::getDefaultValueId('project_status'); 
				$model->project_status_id=ProjectStatus::_NEEDSACTION; //Needs action
				$model->project_currency_id=\livefactory\models\DefaultValueModule::getDefaultValueId('currency');
				$model->project_source_id=\livefactory\models\DefaultValueModule::getDefaultValueId('project_source');
				$model->project_progress=0;
				$disabled_project_status_id=true;
				$disabled_expected_end_date=false;
				$disabled_expected_start_date=false;
			}
			$dFlag = false;
			if(!empty($_GET['customer_id']))
			{
				$model->customer_id=$_GET['customer_id'];
				$dFlag = true;
			}
			/*if(getUserRoleCounts()>0)	
				$model->customer_id=Yii::$app->user->identity->entity_id;*/
				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL 
				] );

			if ($model->expected_start_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->expected_start_datetime=date('Y-m-d', $model->expected_start_datetime);
			}

			if ($model->expected_end_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->expected_end_datetime=date('Y-m-d', $model->expected_end_datetime);
			}

			if ($model->actual_start_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->actual_start_datetime=date('Y-m-d', $model->actual_start_datetime);
			}

			if ($model->actual_end_datetime != '')
			{
				date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
				$model->actual_end_datetime=date('Y-m-d', $model->actual_end_datetime);
			}
				
				/*echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [ 
								
								
								'project_id' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Project ID').'...',
												'maxlength' => 255 
										],
										'columnOptions'=>['colspan'=>1], 
								] ,
						] 
				] );*/
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [
								
								// 'parent_project_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Parent Project ID...']],
								'project_name' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Project Name').'...',
												'maxlength' => 255 
										],
										'columnOptions'=>['colspan'=>2], 
								] ,
								'customer_id' => [ 
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
								],
								'project_owner_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Project Owner').'...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(User::find()->orderBy('first_name')->where("NOT EXISTS(select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=tbl_user.id and auth_assignment.item_name=auth_item.name and auth_item.name='Customer') and active=1")->asArray()->all(), 'id',
										function ($user, $defaultValue) {
														$username=$user['username']?$user['username']:$user['email'];
														 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
													})  , 
										'options' => [ 
                                                'prompt' => '--Select  '.Yii::t('app','Project Owner').'--'
                                        ] 
								],
								
								
						]
						 
				]
				 );
				
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [
								
							
								
								'project_type_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Project Type').' ID...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(ProjectType::find()->where("active=1")->orderBy ( 'sort_order' )->asArray()->all(), 'id', 'label')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Type').'--'
                                        ] 
								],
								
								
								
								'project_status_id' => [
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [
												'placeholder' => 'Enter Project Status ID...'
										] ,
										'items'=>ArrayHelper::map(ProjectStatus::find()->where("active=1")->orderBy ( 'sort_order' )->asArray()->all(), 'id', 'label')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Status').'--',
												'disabled' => $disabled_project_status_id,
                                        ] 
								],
								
							
								'project_priority_id' => [
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [
												'placeholder' => 'Enter Project Priority...'
										] ,
										'items'=>ArrayHelper::map(ProjectPriority::find()->where("active=1")->orderBy ( 'sort_order' )->asArray()->all(), 'id', 'label')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Priority').'--'
                                        ] 
								],
								
								
									'project_currency_id' => [
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [
												'placeholder' => 'Enter Project Currency...'
										] ,
										'items'=>ArrayHelper::map(Currency::find()->orderBy('currency')->orderBy("currency")->asArray()->all(), 'id', 'currency')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Project Currency').'--'
                                        ] 
								],
								
								

							

								
						] 
				]
				 );


		if(Yii::$app->user->identity->entity_type != 'customer')
		{
				
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [
								
								'project_source_id' => [
										'type' => Form::INPUT_DROPDOWN_LIST,
										'options' => [
												'placeholder' => 'Enter Project Source...'
										] ,
										'items'=>ArrayHelper::map(ProjectSource::find()->where("active=1")->orderBy ( 'sort_order' )->asArray()->all(), 'id', 'label')  , 
										'options' => [ 
                                                'prompt' => '--Select '.Yii::t('app','Project Source').'--'
                                        ] 
								],
							
								'project_budget' => [ 
										'type' => Form::INPUT_TEXT,
										
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Amount').'...',
//												'onkeypress' => "return validateFloatKeyPress(this,event);",
												'onchange' => "validateFloatKeyPressOnChange(this);"
										],
								] ,
								
								'project_cost' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Cost').'...',
												'onchange' => "validateFloatKeyPressOnChange(this);"
												
										],
								] ,

								'project_margin' => [ 
										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Margin').'...',
												'onchange' => "validateFloatKeyPressOnChange(this);"
										],
								] ,

								
/*

								'project_items' => [

										'type' => Form::INPUT_TEXT,
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app','Number').'...',
										],
								] ,

*/								
								
						] 
				]
				 );
		}
				
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 4,
						'attributes' => [ 
								
								'expected_start_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
										'options'=>[
											'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
								],
								'expected_end_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
										'options'=>[
											'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											'readonly' => true,
										]
								],
								
								'actual_start_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
										'options'=>[
											'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											//'disabled' => $disabled_actual_start_date,
											'readonly' => true,
										]
								],
								
								'actual_end_datetime' => [ 
										'type'=> Form::INPUT_WIDGET, 'widgetClass'=>DatePicker::classname(),
										'options'=>[
											'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
											'pluginOptions' => [
																  'autoclose'=>true,
																  'format' => 'yyyy-mm-dd',
																  'todayHighlight' => true,
																  //'endDate' => '0d'
															],
											//'disabled' => $disabled_actual_end_date,
											'readonly' => true,
										] 
								] 
						] 
				] );
				if(!empty($_GET['id'])){
					echo $form->field ( $model, 'project_progress' )->widget ( Slider::classname (), [

						

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
				}
				if(!!empty($_REQUEST['id'])){
				echo Form::widget ( [ 
						
						'model' => $model,
						'form' => $form,
						'columns' => 1,
						'attributes' => [ 
								
								'project_description' => [ 
										'type' => Form::INPUT_TEXTAREA,
										'options' => [ 
												'placeholder' => 'Enter Project Description...',
												'rows' => 10
										] 
								] 
						] 
				] );
				
				$form->field ( $model, 'project_description' )->widget ( CKEditor::className (), [ 
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
			
			/*
				if(!!empty($_REQUEST['id'])){
				echo '<div class="row">
                <div class="col-sm-12">
					<div class="form-group">
                    <label class="control-label" for="lname">'.Yii::t('app', 'Notes').':

                    </label>
                    <div class="controls">
                      <textarea class="form-control input-sm ckeditor" name="notes" id="notes" rows="8" style="width:100%"></textarea>
                    </div>
                </div>
				</div>
			</div>';	
				}
				*/
				echo '<input type="hidden" name="Project[project_status_id]" value="'.$model->project_status_id.'">';	
				echo Html::submitButton ( $model->isNewRecord ? Yii::t('app','Create') :Yii::t('app', 'Update'), [ 
						'class' => $model->isNewRecord ? 'btn btn-success  btn-sm' : 'btn btn-primary  btn-sm' 
				] );
				
				}
				
				/*if(!empty($_REQUEST['id'])){
				echo '<input type="hidden" name="Project[customer_id]" value="'.$model->customer_id.'">';	
				}
				if(!empty($_GET['customer_id']))
				echo '<input type="hidden" name="Project[customer_id]" value="'.$_GET['customer_id'].'">';
				if(getUserRoleCounts()>0)	
				echo '<input type="hidden" name="Project[customer_id]" value="'.Yii::$app->user->identity->entity_id.'">';
				$user_ids = array($model->project_owner_id,1);
				
				if(!in_array(Yii::$app->user->identity->id,$user_ids)){
						echo '<input type="hidden" name="Project[project_owner_id]" value="'.$model->project_owner_id.'">';
					}*/
				
ActiveForm::end ();
				?>
</div>
				<script type="text/javascript">
function validateFloatKeyPressOnChange(el) {
    var v = parseFloat(el.value);
    el.value = (isNaN(v)) ? '' : v.toFixed(2);
}

   function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
}

//thanks: http://javascript.nwbox.com/cursor_position/
function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}

</script>