<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var livefactory\models\InvoiceDetails $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="invoice-details-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'invoice_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Invoice ID...']], 

'product_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Product ID...']], 

'product_description'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter Product Description...','rows'=> 6]], 

'payment_amount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Payment Amount...', 'maxlength'=>10]], 

'payment_method'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Payment Method...', 'maxlength'=>25]], 

'notes'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Notes...', 'maxlength'=>255]], 

'rate'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Rate...', 'maxlength'=>10]], 

'quantity'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Quantity...', 'maxlength'=>10]], 

'tax_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tax ID...']], 

'tax_amount'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Tax Amount...', 'maxlength'=>10]], 

'total'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Total...', 'maxlength'=>10]], 

'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 

'added_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Added At...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
