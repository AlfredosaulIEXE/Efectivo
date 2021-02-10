<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\AuthItem;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var livefactory\models\AuthItemChild $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-item-child-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'parent'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Parent...', 'maxlength'=>64]], 
'parent' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 

												'placeholder' => 'Enter '.Yii::t('app','Parent').'...' 

										] ,

										'items'=>ArrayHelper::map(AuthItem::find()->asArray()->all(), 'name','name')  , 

										'options' => [ 

                                                'prompt' => '--Select  '.Yii::t('app','Parent').'--'

                                        ] 

								],
//'child'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Child...', 'maxlength'=>64]], 
'child' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 

												'placeholder' => 'Enter '.Yii::t('app','Child').'...' 

										] ,

										'items'=>ArrayHelper::map(AuthItem::find()->asArray()->all(), 'name','name')  , 

										'options' => [ 

                                                'prompt' => '--Select  '.Yii::t('app','Child').'--'

                                        ] 

								],
    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
