<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Currency $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="currency-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

'currency'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Currency').'...', 'maxlength'=>255]], 

'alphabetic_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Alphabetic Code').'...', 'maxlength'=>255]], 

'numeric_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Numeric Cod').'e...', 'maxlength'=>255]], 

'minor_unit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter '.Yii::t('app', 'Minor Unit').'...', 'maxlength'=>255]],
]

    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']);
    ActiveForm::end(); ?>

</div>
