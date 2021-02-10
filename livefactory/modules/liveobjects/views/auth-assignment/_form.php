<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use livefactory\models\User;
use livefactory\models\AuthItem;
/**
 * @var yii\web\View $this
 * @var livefactory\models\AuthAssignment $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

//'item_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Item Name...', 'maxlength'=>64]], 
'item_name' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 

												'placeholder' => 'Enter '.Yii::t('app','Item Name').'...' 

										] ,

										'items'=>ArrayHelper::map(AuthItem::find()->asArray()->all(), 'name','name')  , 

										'options' => [ 

                                                'prompt' => '--Select  '.Yii::t('app','Item Name').'--'

                                        ] 

								],
//'user_id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter User ID...', 'maxlength'=>64]], 
'user_id' => [ 

										'type' => Form::INPUT_DROPDOWN_LIST,

										'options' => [ 

												'placeholder' => 'Enter '.Yii::t('app','User').'...' 

										] ,

										'items'=>ArrayHelper::map(User::find()->orderBy('first_name')->where("active=1")->asArray()->all(), 'id',
										function ($user, $defaultValue) {
       								$username=$user['username']?$user['username']:$user['email'];
       								 return $user['first_name'] . ' ' . $user['last_name'].' ('.$username.')';
    })  , 

										'options' => [ 

                                                'prompt' => '--Select  '.Yii::t('app','User').'--'

                                        ] 

								],
//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
