<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketCategory $model
 */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!empty($_GET['parent_id'])){
	$model->department_id = $parent->department_id;
}
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
/*$(document).ready(function(){
if('<?=$_GET['parent_id']?'yes':'no'?>' == 'yes')
	$('.field-ticketcategory-department_id').closest('.row').hide()

});*/

</script>
<div class="ticket-category-create">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= $this->title?> <?=!empty($_GET['parent_id'])?'SubCategory in Category - '.$parent->label:'Category'?></h5>

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

                    </div>

                </div>
</div>
