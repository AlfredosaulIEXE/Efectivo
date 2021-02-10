<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\QueueMap $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Queue Map',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Queue Maps'), 'url' => ['index']];
///$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script>
$(document).ready(function(e) {
	$('#queuemap-department_id').change(function(){
    	$.post('index.php?r=liveobjects/queue-map/ajax-get-queue&id='+$(this).val()+'&que_id=',function(result){
			$('#queuemap-queue_id').html(result);
		})
		$.post('index.php?r=liveobjects/queue-map/ajax-get-category1&id='+$(this).val()+'&cat_id=',function(result){
			$('#queuemap-ticket_category_id_2').html(result);
		})
	})
	$('#queuemap-ticket_category_id_2').change(function(){
		$.post('index.php?r=liveobjects/queue-map/ajax-get-category2&id='+$(this).val()+'&cat_id=',function(result){
			$('#queuemap-ticket_category_id_2_id').html(result);
		})
	})
	$('#queuemap-queue_id').load('index.php?r=liveobjects/queue-map/ajax-get-queue&id=<?=$model->department_id?>&que_id=<?=$model->queue_id?>')
	$('#queuemap-ticket_category_id_2').load('index.php?r=liveobjects/queue-map/ajax-get-category1&id=<?=$model->department_id?>&cat_id=<?=$model->ticket_category_id_2?>')
	$('#queuemap-ticket_category_id_2_id').load('index.php?r=liveobjects/queue-map/ajax-get-category2&id=<?=$model->ticket_category_id_2?>&cat_id=<?=$model->ticket_category_id_2_id?>')
});
</script>
<div class="queue-map-update">

    <div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= $this->title ?></h5>

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

										 <div class="project-create">

							<?= $this->render('_form', [

								'model' => $model,

							]) ?>

						

						</div>

                    </div>

                </div>
</div>
