<?php


use livefactory\models\Office;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use livefactory\models\search\UserType as UserTypeSearch;


/**

 * @var yii\web\View $this

 * @var common\models\User $model

 */



$this->title = Yii::t('app', 'Create User', [

    'modelClass' => 'User',

]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

$offices = ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'code');

?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
function addError(obj,error){

	$(obj).parent().addClass('has-error');

	$(obj).next('.help-block').text(error);

}

function removeError(obj){

	$(obj).parent().removeClass('has-error');

	$(obj).next('.help-block').text('');

}
	$(document).ready(function(e) {
        if($('#user-user_type_id').val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){
				 $('.field-user-entity_id').show();
			}else{
				 $('.field-user-entity_id').hide();
			}
		$('#user-user_type_id').change(function(){
            if($(this).val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){
				 $('.field-user-entity_id').show();
			}else{
				 $('.field-user-entity_id').hide();
			}
		})
		$('#w0').submit(function(){
			if($('#user-user_type_id').val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){

				if($('#user-entity_id').val() == '')
				{
					addError($('#user-entity_id'),'<?=Yii::t ('app','This Field is Required!')?>');

					return false;
				}
			}else{
				 removeError($('#user-entity_id'));
			}

            if($('#user-user_type_id').val() ==<?=UserTypeSearch::getCompanyUserType('Customer')->id?>){
				 $('.field-user-entity_id').show();
			}else{
				 $('.field-user-entity_id').hide();
			}
		});

        var offices = <?=json_encode($offices)?>;

        $('#useroffice').on('change', function (e) {
            var selected = $(e.target).val();
            var officeCode = offices[selected] || '';

            $('#officecode').text(officeCode);
        });
    });
</script>
<div class="user-create">

	<!--

    <div class="page-header">

        <h1><?= Html::encode($this->title) ?></h1>

    </div>

	-->

    <?= $this->render('_form', [

        'model' => $model,

    ]) ?>



</div>

