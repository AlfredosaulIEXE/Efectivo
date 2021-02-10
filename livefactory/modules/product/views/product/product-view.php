<?php

use yii\helpers\Html;
use livefactory\models\search\CommonModel;
use livefactory\models\FileModel;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Product $model
 */

$this->title = Yii::t('app', 'Update Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<style>	
.cke_contents{max-height:250px}
.slider .tooltip.top {
    margin-top: -36px;
    z-index: 100;
}
.close {
    color: #000000;
    float: right;
    font-size: 18px;
    font-weight: bold;
    line-height: 1;
    opacity: 0.2;
    text-shadow: 0 1px 0 #ffffff;
}
</style>
<script type="text/javascript">
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
   $('.tabbable').appendTo('#w0');
    //console.log($('a[data-toggle="tab"]:first').tab('show'))
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        //save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab_leadview', $(this).attr('href'));
    });

    //go to the latest tab, if it exists:
    var lastTab_leadview = localStorage.getItem('lastTab_leadview');
    if ($('a[href="' + lastTab_leadview + '"]').length > 0) {
        $('a[href="' + lastTab_leadview + '"]').tab('show');
    }
    else
    {
        // Set the first tab if cookie do not exist
        $('a[data-toggle="tab"]:first').tab('show');
    }
	if('<?=!empty($_REQUEST['attach_update'])?$_REQUEST['attach_update']:''?>' !=''){

		$('.popup').modal('show');

		

	}
});
</script>
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title)?></h5>

                        <div class="ibox-tools">

						    <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

							<!--

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                                <i class="fa fa-wrench"></i>

                            </a>

                            <ul class="dropdown-menu dropdown-user">

                                <li><a href="#">Config option 1</a>

                                </li>

                                <li><a href="#">Config option 2</a>

                                </li>

                            </ul>

							-->

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

                    <div class="ibox-content">

										 <div class="product-create">

							 <?= $this->render('_form', [
								'model' => $model,
							]) ?>

							<!--<div class="tabbable">
                                <ul class="nav nav-tabs">
                                <li class="active"><a href="#description" role="tab" data-toggle="tab"><?php echo Yii::t('app', 'Description'); ?></a></li>
                                <li><a href="#attachments" role="tab" data-toggle="tab"><?=Yii::t('app', 'Attachments')?>

                 <span class="badge"> <?= FileModel::getAttachmentCount('product',$model->id)?></span></a></li>
                                
                                </ul>
                            
                            <!--<div class="tab-content">
                                <div class="tab-pane fade" id="attachments"> 
                                <br/>			
                                         <?php
                                            
                                         /*$searchModelAttch = new CommonModel();
                                        $dataProviderAttach = $searchModelAttch->searchAttachments( Yii::$app->request->getQueryParams (), $model->id,'product');
                                        
                                        echo Yii::$app->controller->renderPartial("../../../liveobjects/views/file/attachment-module/attachments", [ 
                                                'dataProviderAttach' => $dataProviderAttach,
                                                'searchModelAttch' => $searchModelAttch,
												'entity_type'=>'product',
                                        ] );*/
                                            
                                            ?>    
                                </div>
                                <div class="tab-pane  active" id="description"> 
                                <br/>			
                                        <?php

           				/* echo '<div class="col-sm-12">

                                <div class="form-group">

                                <label class="control-label" for="lname">'.Yii::t('app', 'Description').':

            

                                </label>

                                <div class="controls">

                                  <textarea class="form-control input-sm ckeditor" name="Product[product_description]" id="product_description" rows="8" style="width:100%">'.$model->product_description.'</textarea>

                                </div>

                            </div>

                            </div>';*/
						 ?>    
                                </div>
                            </div>-->
                            <?php
           /* echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 
            
                                    'class' => $model->isNewRecord ? 'btn btn-success product_submit' : 'btn btn-primary btn-sm  product_submit' 
            
                            ] );*/?>
                         <!-- </div>-->
                          

						</div>

                    </div>

                </div>
                <?php
            
                            //ActiveForm::end ();
                            echo "</form>";
            ?>
<?php
include_once(__DIR__ .'/../../../liveobjects/views/file/attachment-module/attachmentae.php');
?>