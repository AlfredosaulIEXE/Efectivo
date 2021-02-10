<?php



use yii\helpers\Html;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;

use kartik\datecontrol\DateControl;
use livefactory\models\Country;
use yii\helpers\ArrayHelper;


/**

 * @var yii\web\View $this

 * @var livefactory\models\State $model

 * @var yii\widgets\ActiveForm $form

 */

?>



<div class="state-form">



    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([



    'model' => $model,

    'form' => $form,

    'columns' => 1,

    'attributes' => [



'state'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter State').'...', 'maxlength'=>255]], 



//'country_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter Country').'...']], 

'country_id' => [ 
										'type' => Form::INPUT_DROPDOWN_LIST,
										'label' => 'Country',
										'options' => [ 
												'placeholder' => 'Enter Country ...' 
										] ,
										'columnOptions'=>['colspan'=>1],
										'items'=>ArrayHelper::map(Country::find()->where('active=1')->orderBy('country')->asArray()->all(), 'id', 'country')  , 
										'options' => [ 
                                                'prompt' => Yii::t('app','--Select Country--')
                                        ] 
								],

//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 



///'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 



//'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 



'state_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Enter State Code').'...', 'maxlength'=>255]],



'active' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										//'label' => 'Active',

										'options' => [ 

												'placeholder' => Yii::t('app', 'Enter State').' ...' 

										] ,

										'columnOptions'=>['colspan'=>1],

										'items'=>array('0'=> Yii::t('app', 'No') ,'1'=> Yii::t('app', 'Yes'))  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t('app', 'Select Status').'--'

                                        ]

								], 



    ]





    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);

    ActiveForm::end(); ?>



</div>

