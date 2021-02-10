<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Address $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="address-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 3,
    'attributes' => [

'address_1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Address 1').'...', 'maxlength'=>255]], 

'address_2'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Address 2').'...', 'maxlength'=>255]], 

'country_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'Country',
										'options' => [ 
												'placeholder' => 'Enter '.Yii::t('app', 'Country').'...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(Country::find()->orderBy('country')->asArray()->where("active=1")->all(), 'id', 'country')  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select Country').'--'
                                        ] 
								],

'state_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'State',
										'options' => [ 
												'placeholder' => 'Enter State ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(State::find()->orderBy('state')->where('id=0')->asArray()->all(), 'id', 'state')  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select State').'--'
                                        ]
								],


'city_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										//'label' => 'City',
										'options' => [ 
												'placeholder' => 'Enter City ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(City::find()->orderBy('city')->where('id=0')->asArray()->all(), 'id', 'city')  , 
										'options' => [ 
                                                'prompt' => '--'.Yii::t('app', 'Select City').'--'
                                        ] 
								],

//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 
'zipcode'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Zipcode').'...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ?  Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
    ActiveForm::end(); ?>

</div>
