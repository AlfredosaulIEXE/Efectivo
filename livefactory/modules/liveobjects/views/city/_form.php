<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\Country;
use livefactory\models\State;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var livefactory\models\City $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'city'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> Yii::t('app', 'Enter City').'...', 'maxlength'=>255]], 


//'state_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter State ID...']], 

//'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 


//'country_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Country ID...']], 
'country_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'Country',
										'options' => [ 
												'placeholder' => 'Enter Country ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(Country::find()->where("active=1")->orderBy('country')->asArray()->all(), 'id', 'country')  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select Country').'--'
                                        ] 
								],
'state_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'State',
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter State').' ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(State::find()->orderBy('state')->where('id=0')->asArray()->all(), 'id', 'state')  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select State').'--'
                                        ]
								],
								'active' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'Active',
										'options' => [ 
												'placeholder' => Yii::t('app', 'Enter State').' ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select').'--'
                                        ]
								],
//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
    ActiveForm::end(); ?>

</div>
