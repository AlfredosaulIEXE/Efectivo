<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\CustomerType $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-type-form">
<?

?>
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Type...', 'maxlength'=>255]], 

'label'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Label...', 'maxlength'=>32]], 

//'sort_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Sort Order...']], 

//'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']], 
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
//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>