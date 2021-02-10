<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Estimate',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
ob_start();
?>
<tr>
    <td>
    <input type="hidden" name="detail_id[]" value="">
    <input type="hidden" name="product_id[]" class="product_id" value="">
    <input type="button" class="rowRemove btn btn-danger" value="<?= Yii::t('app','Remove')?>" /></td>
    <td><div class="form-group"><input type="text" name="description[]" class="form-control description" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="rate[]" class="form-control rate" data-validation="required"></div></td>
    <td><div class="form-group"><input type="text" name="quantity[]" value="1" data-valid-num="required" class="form-control quantity" data-validation="required"></div></td>
    <td>
    	<div class="form-group">
        <select class="form-control tax_id" data-validation="required" name="tax_id[]">
            <option value="">--<?= Yii::t('app','Tax')?>--</option>
            <?php
                foreach($taxList as $taxRow){
            ?>
            <option value="<?=$taxRow->tax_percentage?>"><?=$taxRow->name?></option>
            <?php } ?>
        </select>
        </div>
    </td>
    <td><div class="form-group"><input type="text" style="text-align:right" name="tax_amount[]" readonly class="form-control tax_amount" data-validation="required"></div></td>	
    <td><div class="form-group"><input type="text" readonly style="text-align:right" name="total[]" class="form-control total" data-validation="required"></div></td>
</tr>
<?php
$html = ob_get_clean();
$html =str_replace(PHP_EOL, '', $html);
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<?php $this->registerCssFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.css', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script>
var error='';
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
$(document).ready(function(e) {
    $('.tabbable').appendTo('#w0');
	$('#estimate-date_issued-disp').val($('#estimate-date_issued').val()=='0000-00-00 00:00:00'?'':'<?=date('Y/m/d H:i:s',strtotime($model->date_issued))?>');
});

	
</script>
<div class="estimate-update">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?></h5>

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
								'taxList'=>$taxList,
							]) ?>

                    </div>

                </div>
                <div class="tabbable">
				
                <ul class="nav nav-tabs">
                <li class="active"><a href="#notes" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Notes'); ?></a></li>
				 <li><a href="#estimateDetails" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Estimate Details'); ?></a></li>
               
                
                </ul>
            
            <div class="tab-content">
                <div class="tab-pane active" id="notes"> 
                <br/>	
                	<div class="row">

                    <div class="col-sm-12">

                        <div class="form-group">

                        <label class="control-label" for="lname"><?=Yii::t('app', 'Notes')?>

    

                        </label>

                        <div class="controls">

                          <textarea class="form-control input-sm ckeditor" name="Estimate[notes]" id="notes" rows="8" style="width:100%"><?=$model->notes?></textarea>

                        </div>

                    </div>

                    </div>

                </div>
                </div>
                <div class="tab-pane fade" id="estimateDetails"> 
                <br/>	
                	<?php
					   $searchObj  = new \livefactory\models\search\EstimateDetails ;
					   $dataProvider = $searchObj->searchEstimate(Yii::$app->request->getQueryParams(),$model->id) ; 
					   echo Yii::$app->controller->renderPartial("estimate-detail_tab",["dataProvider"=>$dataProvider]);
					  ?>
                </div>
            </div>
            <?php
				echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 
						'class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm' 
				] );
				echo "</form>";
			?>
          </div>
</div>
