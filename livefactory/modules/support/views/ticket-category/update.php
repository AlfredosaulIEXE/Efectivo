<?php
use yii\helpers\Html;
/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketCategory $model
 */
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Ticket Category',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Category'), 'url' => ['index']];
///$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<style>	
.cke_contents{max-height:250px}

</style>
<script type="text/javascript">
$(document).ready(function(e) {
		if(<?=$_GET['id']?>){
        	$('#ticketcategory-name').attr('readonly',true);
		} 
    });
function Add_Error(obj,msg){
	 $(obj).parents('.form-group').addClass('has-error');
	 $(obj).parents('.form-group').append('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_Error(obj){
	$(obj).parents('.form-group').removeClass('has-error');
	$(obj).parents('.form-group').children('.error').remove();
	return false;
}
function Add_ErrorTag(obj,msg){
	obj.css({'border':'1px solid #D16E6C'});
	
	obj.after('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');
	 return true;
}
function Remove_ErrorTag(obj){
	obj.removeAttr('style').next('.error').remove();
	return false;
}
   
$(document).ready(function(){
	CKEDITOR.config.autoParagraph = false;
    $('.tabbable').appendTo('#w0');
});
</script>
<div class="ticket-category-update">
<div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= $this->title?></h5>
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
<div class="tabbable">
        <ul class="nav nav-tabs">
        <li class="active"><a href="#desc" role="tab" data-toggle="tab"><?= Yii::t('app', 'Description')?></a></li>
        <li><a href="#sub_category" role="tab" data-toggle="tab"><?= Yii::t('app', 'Sub Category')?>	
        </a></li>
        </ul>
    
    <div class="tab-content">
    <div class="tab-pane  active" id="desc"> 
    <br/>
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label class="control-label" for="lname"><?=Yii::t('app', 'Description')?>
    
                        </label>
                        <div class="controls">
                          <textarea class="form-control input-sm ckeditor" name="TicketCategory[description]" id="TicketCategory_description" rows="8" style="width:100%"><?=$model->description?></textarea>
                        </div>
                    </div>
                    </div>
    </div>
    <div class="tab-pane" id="sub_category"> 
    <br/>
               <?php
                                
                                $searchModel = new \livefactory\models\search\TicketCategory();
                                $dataProvider= $searchModel->searchSubCategory( Yii::$app->request->getQueryParams (), $model->id);
                                
                                echo Yii::$app->controller->renderPartial("sub-category-tab", [ 
                                        'dataProvider' => $dataProvider,
                                        //'searchModelAttch' => $searchModelAttch,
                                       // 'task_id'=>$model->id
                                ] );
                                
                                ?>
    </div>
    </div>
    <?php
    echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create' ): Yii::t('app', 'Update'), [ 
    
                            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm' 
    
                    ] );?>
                   
                    <?php
                    echo "</form>";
    ?>
    </div>
</div>