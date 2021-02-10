<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\ProductCategory;
use dosamigos\ckeditor\CKEditor;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Product $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); 
	
	echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

		'product_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Product Name...', 'maxlength'=>200]],

								

								'product_category_id' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 
												'prompt' => '--'.Yii::t ( 'app', 'Product Category' ).'--',

										],

										'items' => ArrayHelper::map ( ProductCategory::find ()->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' )  

								], 
								
								'product_price'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Product Price...']], 

								'active' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
									//	'label' => 'Status',
										'options' => [ 
												'placeholder' => 'Enter Active ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select').'--'
                                        ]
								],


    ]


    ]);

	//if(!!empty($_REQUEST['id'])){

				echo Form::widget ( [ 

						

						'model' => $model,

						'form' => $form,

						'columns' => 1,

						'attributes' => [ 

								

								'product_description' => [ 

										'type' => Form::INPUT_TEXT,

										'options' => [ 

												'placeholder' => 'Enter Product Description...',

										] 

								] 

						] 

				] );

				
				echo Html::submitButton ( $model->isNewRecord ? Yii::t('app','Create') :Yii::t('app', 'Update'), [ 

						'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' 

				] );

			//	}

				ActiveForm::end ();

				?>

</div>
