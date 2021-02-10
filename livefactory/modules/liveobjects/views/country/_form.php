<?php



use yii\helpers\Html;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;


use kartik\datecontrol\DateControl;
use livefactory\models\Region;
use yii\helpers\ArrayHelper;

/**

 * @var yii\web\View $this

 * @var livefactory\models\Country $model

 * @var yii\widgets\ActiveForm $form

 */

?>



<div class="country-form">



    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([



    'model' => $model,

    'form' => $form,

    'columns' => 1,

    'attributes' => [



'country'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Country').'...', 'maxlength'=>100]], 



//'region_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Region').'...']],
 'region_id' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'label' => Yii::t('app', 'Region'),

										'options' => [ 

												'placeholder' => 'Enter Region ...' 

										] ,

										'items'=>ArrayHelper::map(Region::find()->asArray()->orderBy('region')->all(), 'id', 'region')  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t('app', 'Select Region').'--'

                                        ] 

								],



//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 



//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 



//'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 

									 'active' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 

												'placeholder' => Yii::t('app', 'Enter State').' ...' 

										] ,

										'columnOptions'=>['colspan'=>1],

										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t('app', 'Select Status').'--'

                                        ]

								],



'country_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Country Code').'...', 'maxlength'=>10]]



    ]





    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);

    ActiveForm::end(); ?>



</div>

