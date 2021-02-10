<?php
use yii\helpers\Html;

use yii\bootstrap\ActiveForm;



/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */



$this->title = Yii::t('app', 'Truncate Table');
use livefactory\models\User;
$this->params['breadcrumbs'][] = $this->title;
$adminUser = User::find()->where("username='admin'")->one();
?>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<?php $this->registerCssFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.css', ['depends' => [yii\web\YiiAsset::className()]]);?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/autocomplete/jquery-ui.js', ['depends' => [yii\web\YiiAsset::className()]]);?>

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

$(document).ready(function(e) {

	

	$('.truncateTable').click(function(event){

			var error='';
			$('[data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if($('#verified_code').val() != ''){
			if($('#verified_code').val() != '<?=Yii::$app->params['TRUNCATE_TABLE'] ?>'){
				error+=Add_Error($('#verified_code'),'<?=Yii::t('app', 'Verified Code Wrong!')?>');
			}else{
				Remove_Error($('#verified_code'));
			}
		}
		if(error==''){
			var r = confirm("<?=Yii::t('app', 'Are you Sure!')?>");
			if (r != true) {
					event.preventDefault();
				}
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('#all').click(function(event){

			var error='';
				Remove_Error($('#verified_code1'));	
				if($('#verified_code1').val() == ''){

					error+=Add_Error($('#verified_code1'),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{
					if($('#verified_code1').val() != '<?=Yii::$app->params['TRUNCATE_TABLE'] ?>'){
						error+=Add_Error($('#verified_code1'),'<?=Yii::t('app', 'Verified Code Wrong!')?>');
					}else{
						Remove_Error($('#verified_code1'));
					}
				}

		if(error==''){
			var r = confirm("<?=Yii::t('app', 'Are you Sure!')?>");
			if (r != true) {
					event.preventDefault();
				}
			document.frm.submit();

		}else{
			
			event.preventDefault();

		}

	})
	if('<?= isset($_GET['sent'])?'y':'n'?>' =='y'){
		setTimeout(function(){
			window.location.href='index.php?r=liveobjects/setting/truncate-table';
		},2000)
	}
 });

</script>
 <script>
$(function() {
var availableTags = <?= json_encode($allTables)?>;
$( "#table_name" ).autocomplete({
source: availableTags
});
});
</script>

<h3></h3>
<div class="row">
	<div class="col-sm-12">
    	<?php if(!empty($error)){?>

            	<div class="alert alert-danger"><?=$error?></div>

            <?php } ?>
            <?php if(!empty($msg)){?>

            	<div class="alert alert-success"><?=$msg?></div>

            <?php } ?>
            <?php if(!empty($_GET['sent'])){?>

            	<div class="alert alert-success">An email has been sent to (<?=$adminUser->email?>) with Verification Code, enter the code below and continue</div>

            <?php } ?>
    </div>
</div>
<div class="col-lg-6">

    <div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5><?=Yii::t('app','Empty Table')?></h5>

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

        	
            <form  action="" method="post" enctype="multipart/form-data">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">

                	<label><?=Yii::t('app', 'Verification Code')?></label>

                    <input type="text" name="verified_code" class="form-control" id="verified_code" data-validation="required">

                </div>
                <div class="form-group">

                	<label><?=Yii::t('app', 'Table Name')?></label>

                    <input type="text" name="table_name" class="form-control" id="table_name" data-validation="required">

                </div>

                <?= Html::submitButton(Yii::t('app', 'Empty Table'), ['class' => 'btn btn-danger truncateTable btn-sm']) ?>
				<a href="index.php?r=liveobjects/setting/truncate-table&send_varified_code=true"  class="btn btn-primary pull-right btn-sm"> <?=Yii::t('app', 'Send Verification Code')?></a>
		



            </form>

        </div>

    </div>

</div>
<div class="col-lg-6">

    <div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5><?=Yii::t('app','Empty All Tables')?></h5>

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
			<form  action="" name="frm" method="post" enctype="multipart/form-data">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="all_table_empty" value="true">
    
    	</form>
                <div class="form-group">

                	<label><?=Yii::t('app', 'Verification Code')?></label>

                    <input type="text" name="verified_code1" class="form-control" id="verified_code1" >

                </div>
                <input type="button" value="<?=Yii::t('app', 'Empty All Table')?>" id="all" class="btn btn-danger btn-sm">
                <a href="index.php?r=liveobjects/setting/truncate-table&send_varified_code=true"  class="btn btn-primary pull-right btn-sm"><?=Yii::t('app', 'Send Verification Code')?></a>

		



            </form>

        </div>

    </div>

</div>

            

