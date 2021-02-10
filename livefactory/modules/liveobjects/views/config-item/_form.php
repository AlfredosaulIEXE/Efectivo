<?php



use yii\helpers\Html;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;

use kartik\datecontrol\DateControl;



/**

 * @var yii\web\View $this

 * @var livefactory\models\ConfigItem $model

 * @var yii\widgets\ActiveForm $form

 */

?>



<div class="config-item-form">



    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([



    'model' => $model,

    'form' => $form,

    'columns' => 1,

    'attributes' => [



//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 



//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']], 



'config_item_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Config Item Name...', 'maxlength'=>255]], 



'config_item_value'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Config Item Value...', 'maxlength'=>255]], 



'config_item_description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Config Item Description...', 'maxlength'=>255]], 



//'active'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Active...']], 

'active' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										//'label' => 'Status',

										'options' => [ 

												'placeholder' => 'Enter Active ...' 

										] ,

										'columnOptions'=>['colspan'=>1],

										'items'=>array('0'=>Yii::t('app', 'No'),'1'=>Yii::t('app', 'Yes'))  , 

										'options' => [ 

                                                'prompt' => '--'.Yii::t('app', 'Select').'--'

                                        ]

								],


    ]





    ]);

    echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

    ActiveForm::end(); ?>



</div>

