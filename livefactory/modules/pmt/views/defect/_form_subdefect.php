<?php







use yii\helpers\Html;



use yii\helpers\ArrayHelper;



use kartik\widgets\ActiveForm;



use kartik\builder\Form;



use kartik\datecontrol\DateControl;



use dosamigos\ckeditor\CKEditor;



use livefactory\models\Project;



use livefactory\models\DefectStatus;



use livefactory\models\DefectPriority;
use livefactory\models\DefectType;



use livefactory\models\User;



use kartik\slider\Slider;







/**



 *



 * @var yii\web\View $this



 * @var common\models\Defect $model



 * @var yii\widgets\ActiveForm $form



 */



?>





<div class="defect-form">

    <?php



				



				$form = ActiveForm::begin ( [ 



						'type' => ActiveForm::TYPE_VERTICAL 



				] );



				



				echo Form::widget ( [ 



						



						'model' => $sub_defect,



						'form' => $form,



						'columns' => 1,



						'attributes' => [ 



								



								'defect_name' => [ 



										'type' => Form::INPUT_TEXT,



										'options' => [ 



												'placeholder' => 'Enter '.Yii::t ( 'app', 'Defect Name' ).'...',



												'maxlength' => 1024 



										] 



								] 



						] 



				] );



				 

				if(Yii::$app->params['user_role'] == 'admin'){

					$projects =ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->asArray ()->all (), 'id', 'project_name' ) ;	

				}else{

					$projects=ArrayHelper::map ( Project::find ()->orderBy ( 'project_name' )->where("EXISTS(Select *

FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")")->asArray ()->all (), 'id', 'project_name' ) ;	

				}

				echo Form::widget ( [ 



						



						'model' => $sub_defect,



						'form' => $form,



						'columns' => 4,



						'attributes' => [



								



								// 'defect_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Defect ID...', 'maxlength'=>255]],



								



								/*'project_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'options' => [ 



												'placeholder' => 'Enter Project ID...' 



										],



										'items' => $projects  , 

										'options' => [ 

                                                'prompt' => '--Select Project--'

                                        ] 



								],*/



								



								'defect_status_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'options' => [ 



												'placeholder' => 'Enter Defect Status ID...' 



										],



										'items' => ArrayHelper::map ( DefectStatus::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )   , 

										'options' => [ 

                                                'prompt' => '--Select '.Yii::t ( 'app', 'Status' ).'--'

                                        ] 



								],



								



								'defect_priority_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'options' => [ 



												'placeholder' => 'Enter '.Yii::t ( 'app', 'Defect Priority' ).' ...' 



										],



										'items' => ArrayHelper::map ( DefectPriority::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )   , 

										'options' => [ 

                                                'prompt' => '--Select '.Yii::t ( 'app', 'Defect Priority' ).'--'

                                        ] 



								],
								'defect_type_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'options' => [ 



												'placeholder' => 'Enter '.Yii::t ( 'app', 'Defect Type' ).' ...' 



										],



										'items' => ArrayHelper::map ( DefectType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )   , 

										'options' => [ 

                                                'prompt' => '--Select '.Yii::t ( 'app', 'Defect Type' ).'--'

                                        ] 



								],



								



								'user_assigned_id' => [ 



										'type' => Form::INPUT_DROPDOWN_LIST,



										'options' => [ 



												'placeholder' => 'Enter Assigned User ID...' 



										],



										'items' => ArrayHelper::map ( User::find()->where("active=1")->orderBy ( 'first_name' )->asArray ()->all (), 'id', function ($user, $defaultValue) {
       								 $username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    })  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t ( 'app', 'Assigned User' ).'--'

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



								



								



								







								/*'defect_progress' => [ 



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



				



				/*echo Form::widget ( [ 



						



						'model' => $sub_defect,



						'form' => $form,



						'columns' => 4,



						'attributes' => [ 



								



								'expected_start_datetime' => [ 



										'type' => Form::INPUT_WIDGET,



										'widgetClass' => DateControl::classname (),

										'value'=>$model->expected_start_datetime,



										'options' => [ 



												'type' => DateControl::FORMAT_DATETIME,

												'id'=>'ddddd' 



										] 



								],



								'expected_end_datetime' => [ 



										'type' => Form::INPUT_WIDGET,



										'widgetClass' => DateControl::classname (),



										'options' => [ 



												'type' => DateControl::FORMAT_DATETIME 



										] 



								],



								



								// 'date_added'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],



								



								// 'date_modified'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],



								



								'actual_end_datetime' => [ 



										'type' => Form::INPUT_WIDGET,



										'widgetClass' => DateControl::classname (),



										'options' => [ 



												'type' => DateControl::FORMAT_DATETIME 



										] 



								],



								



								'actual_start_datetime' => [ 



										'type' => Form::INPUT_WIDGET,



										'widgetClass' => DateControl::classname (),



										'options' => [ 



												'type' => DateControl::FORMAT_DATETIME 



										] 



								] 



						] 



				] );

*/

				



				



				



				?><div class="row">

             <div class="col-sm-3">

                <div class="form-group">

                    <label class="control-label" for="name"><?=Yii::t('app','Expected Start Datetime')?>:</label>

               <div class="input-group date form_datetime1" data-date="" 

               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">

                     <span class="input-group-addon" title="Select date & time">

                        <span class="glyphicon glyphicon-calendar"></span>

                     </span>

                    <span class="input-group-addon" title="Clear field">

                    	<span class="glyphicon glyphicon-remove"></span>

                    </span>

					<input type="text" class="form-control input-sm expected_start_datetime" name="Defect[expected_start_datetime]" value=""/>

               </div>

                </div>

            </div>

			 <div class="col-sm-3">

                <div class="form-group">

                    <label class="control-label" for="lname"><?=Yii::t('app','Expected Start Datetime')?>:</label>

                    <div class="input-group date form_datetime2" data-date="" 

               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">

                     <span class="input-group-addon" title="Select date & time">

                        <span class="glyphicon glyphicon-calendar"></span>

                     </span>

                    <span class="input-group-addon" title="Clear field">

                    	<span class="glyphicon glyphicon-remove"></span>

                    </span>

					<input type="text" class="form-control input-sm expected_end_datetime" name="Defect[expected_end_datetime]"  />

					

               </div>

                </div>

            </div>

         	 <div class="col-sm-3">

                <div class="form-group">

                    <label class="control-label" for="name"><?=Yii::t('app','Actual Start Datetime')?>:</label>

                    <div class="input-group date form_datetime3" data-date="" 

               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">

                     <span class="input-group-addon" title="Select date & time">

                        <span class="glyphicon glyphicon-calendar"></span>

                     </span>

                    <span class="input-group-addon" title="Clear field">

                    	<span class="glyphicon glyphicon-remove"></span>

                    </span>

					<input type="text" class="form-control input-sm actual_start_datetime" name="Defect[actual_start_datetime]" />

               </div>

                </div>

            </div>

			 <div class="col-sm-3">

                <div class="form-group">

                    <label class="control-label" for="lname"><?=Yii::t('app','Actual End Datetime')?>:</label>

                    <div class="input-group date form_datetime4" data-date="" 

               		 data-date-format="yyyy/mm/dd HH:ii:ss" data-link-field="dtp_input1">

                     <span class="input-group-addon" title="Select date & time">

                        <span class="glyphicon glyphicon-calendar"></span>

                     </span>

                    <span class="input-group-addon" title="Clear field">

                    	<span class="glyphicon glyphicon-remove"></span>

                    </span>

					<input type="text" class="form-control input-sm actual_end_datetime" name="Defect[actual_end_datetime]" />

               </div>

                </div>

            </div>

			</div><?php

				

				/*echo Form::widget ( [ 



						



						'model' => $sub_defect,



						'form' => $form,



						'columns' => 1,



						'attributes' => [ 



								



								'defect_description' => [ 



										'type' => Form::INPUT_TEXTAREA,



										'options' => [ 



												'placeholder' => 'Enter Defect Description...',



												'rows' => 6 



										] 



								] 



						] 



				] );

				$form->field ( $sub_defect, 'defect_description' )->widget ( CKEditor::className (), [ 



						'options' => [ 



								'rows' => 10 



						],



						'preset' => 'basic' 



				] );*/

				echo '<div class="row">

                <div class="col-sm-12">

					<div class="form-group">

                    <label class="control-label" for="lname">'.Yii::t('app','Description').':



                    </label>

                    <div class="controls">

                      <textarea class="form-control input-sm ckeditor" name="Defect[defect_description]"  rows="8" style="width:100%"></textarea>

                    </div>

                </div>

				</div>

			</div>';

				echo "<input type='hidden' name='Defect[parent_id]' value='".$_GET['id']."'>

				<input type='hidden' name='Defect[added_at]' value='".strtotime(date('Y-m-d'))."'>

				<input type='hidden' name='Defect[project_id]' value='".$_SESSION['pid']."'>

				<input type='hidden' name='defectid' value='".$_GET['id']."'>";

				//$form->hiddenField($sub_defect,'parent_id',array('value'=>$_GET['id']));



			echo Html::submitButton ( $sub_defect->isNewRecord ? Yii::t('app','Create') :Yii::t('app','Update') , [ 



						'class' => $sub_defect->isNewRecord ? 'btn btn-success subdefect_insert btn-sm' : 'btn btn-primary subdefect_insert btn-sm' 



				] );



				ActiveForm::end ();

				



				



				?>



</div>



