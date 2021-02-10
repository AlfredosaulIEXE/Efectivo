<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\City $model
 */

$this->title = Yii::t('app', 'Update City : ', [
    'modelClass' => 'City',
]) . ' ' . $model->city;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
function loadState(){
$('#city-state_id').load('<?=$baseUrl?>?r=liveobjects/address/ajax-load-states&country_id='+escape('<?=$model->country_id?>')+'&state_id='+escape('<?=$model->state_id?>'));
		
}
$(document).ready(function(e) {
	$('#city-country_id').change(function(){
    $.post('<?=$baseUrl?>?r=liveobjects/address/ajax-load-states&country_id='+$(this).val(),function(result){
					$('#city-state_id').html(result);
					
				})
	})
	//Auto Load
	loadState();
});


</script>
<div class="city-update">
<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5> <?=$this->title ?></h5>

            <div class="ibox-tools">

                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
               
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
</div>
         <div class="ibox-content">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div></div></div>
