<?php



use yii\helpers\Html;

use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;

use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use kartik\widgets\DepDrop;
use livefactory\models\User;
use kartik\datecontrol\DateControl;

use livefactory\models\CustomerType;



/**

 *

 * @var yii\web\View $this

 * @var common\models\Customer $model

 * @var yii\widgets\ActiveForm $form

 */

?>



<div class="customer-form">

    <?php
				if(empty($_GET['id'])){
					$model->customer_type_id = \livefactory\models\DefaultValueModule::getDefaultValueId('customer_type');
				}
				$customerType = array();
				foreach(ArrayHelper::map ( CustomerType::find()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label') as $key => $ct){
					$customerType[$key]=$ct ;//Yii::t ( 'app', $ct)	;
				}

				$form = ActiveForm::begin ( [ 

						'type' => ActiveForm::TYPE_VERTICAL ,
						'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']]  //this helps to show icons in validation messages 

				] );?>

				<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t ( 'app', 'Customer Details' ); ?></h3>
                </div>
                <div class="panel-body">
			<?php
				echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 3,

						'attributes' => [ 

								

								'customer_name' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Customer Name' ).'...',

												'maxlength' => 255 

										],

										/*'columnOptions' => [ 

												'colspan' => 2 

										] */

								]


								,

								

								'customer_owner_id' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 
												'prompt' => '--'.Yii::t ( 'app', 'Customer Owner' ).'--',

										],

										'items' => ArrayHelper::map(User::find()->where("NOT EXISTS(select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=tbl_user.id and auth_assignment.item_name=auth_item.name and auth_item.name='Customer') and active=1")->asArray()->all(),'id',function($user){
											return $user['first_name'].' '.$user['last_name'].' ('.$user['username'].')';
										})
										  

								]
								,

								

								'customer_type_id' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 
												'prompt' => '--'.Yii::t ( 'app', 'Customer Type' ).'--',

										],

										'items' => $customerType
										  

								]

								 

						] 

				]

				 );

				

				echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 3,

						'attributes' => [ 

								

								'first_name' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter First Name' ).'...',

												'maxlength' => 255 

										] 

								],

								

								'last_name' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Last Name' ).'...',



												'maxlength' => 255 

										] 

								]
								,

								

								'email' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Email' ).'...',

												'maxlength' => 255 

										] 

								],

								

								'phone' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Phone' ).'...',

												'maxlength' => 255 

										] 

								],

								

								'fax' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Fax' ).'...', 

												'maxlength' => 255 

										] 

								],

								

								'mobile' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => Yii::t ( 'app', 'Enter Mobile' ).'...' 

										] 

								] 

						] 

				] );

				

				/*echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 4,

						'attributes' => [ 

								

								'address_id' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => 'Enter Address...' 

										] 

								] 

						] 

				] );*/
				?>
                </div></div>
                <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo Yii::t ( 'app', 'Address Details' ); ?></h3>
                </div>
                <div class="panel-body">
                <div class="row">
                	<div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Address 1' ); ?></label>
                        	<input type="text" name="address_1" data-validation="required" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Address 2' ); ?></label>
                        	<input type="text" name="address_2" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-group">
                        	<label class="control-label"><?php echo Yii::t ( 'app', 'Zipcode' ); ?></label>
                        	<input type="text" name="zipcode" data-validation="required" class="form-control">
                        </div>
                    </div>
                </div>
                <?php
				echo '<div class="row">
						<div class="col-sm-4">
							<div class="form-group required">
								<label class="control-label">'.Yii::t ( 'app', 'Country' ).'</label>
						'.Html::dropDownList('country_id',  \livefactory\models\DefaultValueModule::getDefaultValueId('country'),
     ArrayHelper::map(Country::find()->orderBy('country')->where('active=1')->asArray()->all(), 'id', 'country'), ['prompt' => '--'.Yii::t ( 'app', 'Select' ).'--','class'=>'form-control','id'=>'country_id','data-validation'=>'required' ]  ).'</div></div>
	 					<div class="col-sm-4">
						<div class="form-group required">
								<label class="control-label">'.Yii::t ( 'app', 'State' ).'</label>
						'.Html::dropDownList('state_id', 'state_id',
     ArrayHelper::map(State::find()->where('id=0')->orderBy('state')->asArray()->all(), 'id', 'state'), ['prompt' => '--'.Yii::t ( 'app', 'Select' ).'--','class'=>'form-control','id'=>'state_id', 'data-validation'=>'required']  ).'</div></div>
	 				<div class="col-sm-4">
						<div class="form-group required">
								<label class="control-label">'.Yii::t ( 'app', 'City' ).'</label>
						'.Html::dropDownList('city_id', 'city_id',
     ArrayHelper::map(City::find()->where('id=0')->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--'.Yii::t ( 'app', 'Select' ).'--','class'=>'form-control','id'=>'city_id' ]  ).'</div></div></div></div></div>';
				echo Html::submitButton ( $model->isNewRecord ? Yii::t ( 'app', 'Create' ) : Yii::t ( 'app', 'Update' ), [ 

						'class' => $model->isNewRecord ? 'btn btn-success btn-sm customer_submit' : 'btn btn-primary btn-sm customer_submit' 

				] );

				ActiveForm::end ();

				?>

</div>

