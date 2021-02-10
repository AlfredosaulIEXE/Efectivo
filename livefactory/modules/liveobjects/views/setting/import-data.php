<?php
use livefactory\models\ImportData;
use yii\helpers\Html;

use yii\bootstrap\ActiveForm;



/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */



$this->title = Yii::t('app', 'Import Data');

$this->params['breadcrumbs'][] = $this->title;

if(!empty($_POST['project_csv_upload'])){
	list($msg,$error)=ImportData::project_insert_by_csvfile($_SESSION['customer_id']);
}
if(!empty($_POST['task_csv_upload']) && !empty($_SESSION['project_id'])){
	list($msg,$error)=ImportData::task_insert_by_csvfile($_SESSION['project_id']);
	unset($_SESSION['project_id']);
}
if(!empty($_POST['defect_csv_upload']) && !empty($_SESSION['project_id1'])){
	list($dmsg,$derror)=ImportData::defect_insert_by_csvfile($_SESSION['project_id1']);
	unset($_SESSION['project_id1']);
}
if(!empty($_POST['customer_csv_upload'])){
	list($cmsg,$cerror)=ImportData::customer_insert_by_csvfile();
}
if(!empty($_POST['user_csv_upload'])){
	list($umsg,$uerror)=ImportData::user_insert_by_csvfile();
}
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
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
	
	$('.project_submit').click(function(event){

			var error='';
			$('#project_frm [data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if(error==''){
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('.task_submit').click(function(event){

			var error='';
			$('#task_frm [data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if(error==''){
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('.defect_submit').click(function(event){

			var error='';
			$('#defect_frm [data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if(error==''){
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('.customer_submit').click(function(event){

			var error='';
			$('#customer_frm [data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if(error==''){
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('.user_submit').click(function(event){

			var error='';
			$('#user_frm [data-validation="required"]').each(function(index, element) {

				Remove_Error($(this));

				if($(this).val() == ''){

					error+=Add_Error($(this),'<?=Yii::t('app', 'This Field is Required!')?>');

				}else{

						Remove_Error($(this));							

				}

			});
		if(error==''){
			return;	

		}else{
			
			event.preventDefault();

		}

	})
	$('#project').change(function(){
		$('.upload_task_csv').hide();
	})
	$('#project1').change(function(){
		$('.upload_defect_csv').hide();
	})

	
 });

</script>
<div class="col-lg-12">

    <div class="ibox float-e-margins">

        <div class="ibox-title">

            <h5><?=Yii::t('app', $this->title )?></h5>

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
			<div class="tabbable">

        <ul class="nav nav-tabs">
		<?php if (in_array('pmt',Yii::$app->params['modules'])){ ?>
        <li class="active"><a href="#project" role="tab" data-toggle="tab"><?= Yii::t('app', 'Project')?></a></li>
        <li><a href="#task" role="tab" data-toggle="tab"><?= Yii::t('app', 'Task')?></a></li>
        <li><a href="#defect" role="tab" data-toggle="tab"><?= Yii::t('app', 'Defect')?></a></li>
		<?php } if (in_array('customer',Yii::$app->params['modules'])){ ?>
        <li><a href="#customertab" role="tab" data-toggle="tab"><?= Yii::t('app', 'Customer')?></a></li>
		<?php } if (in_array('user',Yii::$app->params['modules'])){ ?>
         <li><a href="#user" role="tab" data-toggle="tab"><?= Yii::t('app', 'User')?></a></li>
		<?php } ?>
        </ul>

    

    <div class="tab-content">
	<?php if (in_array('pmt',Yii::$app->params['modules'])){ ?>
		<div class="tab-pane  active" id="project"> 
         <br/>
    		<?php if(!empty($error)){?>

            	<div class="alert alert-danger"><?=$error?> <?=Yii::t('app',$error=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'Validation Error')?></div>

            <?php } ?>
            <?php if(!empty($msg)){?>

            	<div class="alert alert-success"><?=$msg?>  <?=Yii::t('app', $msg=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'been Successfully Inserted')?></div>

            <?php } ?>
            <form  action="" id="project_frm" method="post" enctype="multipart/form-data" style="display:inline">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
    <input type="hidden" name="project_csv" value="yes">
                <div class="form-group">
<i><?=Yii::t('app', 'Note: File Type should be csv')?></i>
(
<i style="color:#F00; font-size:12px"><?=Yii::t('app', 'Required Fields*: Customer, project_name,expected_start_datetime, expected_end_datetime, project_type_id, project_currency_id, project_status_id, project_priority_id, project_owner_id')?></i>
)
<br><br>
<label><?=Yii::t('app', 'Customer')?></label>

                    <select name="customer" id="customer" class="form-control" data-validation="required">
                    	<option value="">--<?=Yii::t('app', 'Select Customer')?>--</option>
                    	<?php
							foreach($customers as $customer){
						?>
                        	<option value="<?=$customer['id']?>" <?=!empty($_POST['customer']) && $_POST['customer']==$customer['id']?'selected':''?>><?=$customer['customer_name']?></option>
                        <?php } ?>
                    </select>
					</div>
					<div class="form-group">
                	<label><?=Yii::t('app', 'CSV File')?></label>

                    <input type="file" name="project_csv_file" class="form-control" id="task_csv_file" data-validation="required">

					</div>

                <?= Html::submitButton(Yii::t('app', 'Project CSV'), ['class' => 'btn btn-primary btn-sm project_submit']) ?>
                
				</form>
                <?php if(isset($_POST['project_csv'])){?>
                <form  action="" method="post" enctype="multipart/form-data" style="display:inline">
				  <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                  <input type="hidden" name="project_csv_upload" value="true">	
                   <?= Html::submitButton(Yii::t('app', 'Import File'), ['class' => 'btn btn-sm btn-primary upload_project_csv']) ?>
                </form>
                <?php } ?>
                <a class="btn btn-success btn-sm" target="_blank" href="../../livefactory/csv_files/project_template.csv" data-original-title="" title="">
                    <span class="fa fa-file-excel-o"></span>
                   	<?=Yii::t('app', 'Download Project CSV Import Template')?> 
                    </a>
        </div>
	
        <div class="tab-pane" id="task"> 
         <br/>
    		<?php if(!empty($error)){?>

            	<div class="alert alert-danger"><?=$error?> <?=Yii::t('app',$error=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'Validation Error')?></div>

            <?php } ?>
            <?php if(!empty($msg)){?>

            	<div class="alert alert-success"><?=$msg?>  <?=Yii::t('app', $msg=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'been Successfully Inserted')?></div>

            <?php } ?>
            <form  action="" id="task_frm" method="post" enctype="multipart/form-data" style="display:inline">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">
					
<i><?=Yii::t('app', 'Note: File Type should be "csv"')?></i>
(
<i style="color:#F00; font-size:12px"><?=Yii::t('app', 'Required Fields*: Project, task_name,task_status_id, task_priority_id, task_type_id, user_assigned_id')?></i>
)
<br><br>
                	<label><?=Yii::t('app', 'Project')?></label>

                    <select name="project" id="project" class="form-control" data-validation="required">
                    	<option value="">--<?=Yii::t('app', 'Select Project')?>--</option>
                    	<?php
							foreach($projects as $project){
						?>
                        	<option value="<?=$project['id']?>" <?=!empty($_POST['project']) && $_POST['project']==$project['id']?'selected':''?>><?=$project['project_name']?></option>
                        <?php } ?>
                    </select>

                </div>
                <div class="form-group">

                	<label><?=Yii::t('app', 'CSV File')?></label>

                    <input type="file" name="task_csv_file" class="form-control" id="task_csv_file" data-validation="required">

                </div>

                <?= Html::submitButton(Yii::t('app', 'Task CSV'), ['class' => 'btn btn-primary btn-sm task_submit']) ?>
                
				</form>
                <?php if(isset($_POST['project'])){?>
                <form  action="" method="post" enctype="multipart/form-data" style="display:inline">
				  <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                  <input type="hidden" name="task_csv_upload" value="true">	
                   <?= Html::submitButton(Yii::t('app', 'Import File'), ['class' => 'btn btn-sm btn-primary upload_task_csv']) ?>
                </form>
                <?php } ?>
                <a class="btn btn-success btn-sm" target="_blank" href="../../livefactory/csv_files/task_template.csv" data-original-title="" title="">
                    <span class="fa fa-file-excel-o"></span>
                   	<?=Yii::t('app', 'Download Task CSV Import Template')?> 
                    </a>
        </div>
        <div class="tab-pane" id="defect"> 
         <br/>
    		<?php if(!empty($derror)){?>

            	<div class="alert alert-danger"><?=$derror?> <?=Yii::t('app', $derror=='1'?'Row has':'Rows have')?>  <?=Yii::t('app', 'Validation Error')?></div>

            <?php } ?>
            <?php if(!empty($dmsg)){?>

            	<div class="alert alert-success"><?=$dmsg?>  <?=Yii::t('app', $dmsg=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'been Successfully Inserted')?></div>

            <?php } ?>
            <form  action="" id="defect_frm" method="post" enctype="multipart/form-data" style="display:inline">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">
					
<i><?=Yii::t('app', 'Note: File Type should be "csv"')?></i>
(
<i style="color:#F00; font-size:12px"><?=Yii::t('app', 'Required Fields*: Project, defect_name, user_assigned_id, user_assigned_id, defect_status_id, defect_priority_id')?></i>
)
<br><br>
                	<label><?=Yii::t('app', 'Project')?></label>

                    <select name="project1" id="project1" class="form-control" data-validation="required">
                    	<option value="">--<?=Yii::t('app', 'Select Project')?>--</option>
                    	<?php
							foreach($projects as $project){
						?>
                        	<option value="<?=$project['id']?>" <?=!empty($_POST['project']) && $_POST['project1']==$project['id']?'selected':''?>><?=$project['project_name']?></option>
                        <?php } ?>
                    </select>

                </div>
                <div class="form-group">

                	<label><?=Yii::t('app', 'CSV File')?></label>

                    <input type="file" name="defect_csv_file" class="form-control" id="defect_csv_file" data-validation="required">

                </div>

                <?= Html::submitButton(Yii::t('app', 'Defect CSV'), ['class' => 'btn btn-primary btn-sm defect_submit']) ?>
                
				</form>
                <?php if(isset($_POST['project1'])){?>
                <form  action="" method="post" enctype="multipart/form-data" style="display:inline">
				  <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                  <input type="hidden" name="defect_csv_upload" value="true">	
                   <?= Html::submitButton(Yii::t('app', 'Import File'), ['class' => 'btn btn-sm btn-primary upload_defect_csv']) ?>
                </form>
                <?php } ?>
                <a class="btn btn-success btn-sm" target="_blank" href="../../livefactory/csv_files/defect_template.csv" data-original-title="" title="">
                    <span class="fa fa-file-excel-o"></span>
                   	<?=Yii::t('app', 'Download Defect CSV Import Template')?> 
                    </a>
        </div>
       <?php }   if (in_array('customer',Yii::$app->params['modules'])){ ?>
	<div class="tab-pane" id="customertab"> 
         <br/>
    		<?php if(!empty($cerror)){?>

            	<div class="alert alert-danger"><?=$cerror?> <?=Yii::t('app',$cerror=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'Validation Error')?> </div>

            <?php } ?>
            <?php if(!empty($cmsg)){?>

            	<div class="alert alert-success"><?=$cmsg?>  <?=Yii::t('app', $cmsg=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'been Successfully Inserted')?> </div>

            <?php } ?>
            <form  action="" id="customer_frm" method="post" enctype="multipart/form-data" style="display:inline">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">

<i><?=Yii::t('app', 'Note: File Type should be "csv"')?></i>
(
<i style="color:#F00; font-size:12px"><?=Yii::t('app', 'Required Fields*: first_name, last_name, customer_name, mobile, customer_owner_id, address_1, zipcode, country_id, state_id, customer_type_id, email')?></i>
)
<br><br>
                	<label><?=Yii::t('app', 'CSV File')?></label>

                    <input type="file" name="cus_csv_file" class="form-control" id="cus_csv_file" data-validation="required">

                </div>

                <?= Html::submitButton(Yii::t('app', 'Customer CSV'), ['class' => 'btn btn-primary btn-sm customer_submit']) ?>
                
				</form>
                <?php if(isset($_FILES['cus_csv_file'])){?>
                <form  action="" method="post" enctype="multipart/form-data" style="display:inline">
				  <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                  <input type="hidden" name="customer_csv_upload" value="true">	
                   <?= Html::submitButton(Yii::t('app', 'Import File'), ['class' => 'btn btn-primary btn-sm customer_csv_upload']) ?>
                </form>
                 
                <?php } ?>
                <a class="btn btn-success btn-sm" target="_blank" href="../../livefactory/csv_files/customer_template.csv" data-original-title="" title="">
                    <span class="fa fa-file-excel-o"></span>
                   	 <?=Yii::t('app', 'Download Customer CSV Import Template')?>
                    </a>
        </div>
	   <?php }  if (in_array('user',Yii::$app->params['modules'])){?>
        <div class="tab-pane" id="user"> 
         <br/>
    		<?php if(!empty($uerror)){?>

            	<div class="alert alert-danger"><?=$uerror?> <?=Yii::t('app', $uerror=='1'?'Row has':'Rows have')?>  <?=Yii::t('app', 'Validation Error')?></div>

            <?php } ?>
            <?php if(!empty($umsg)){?>

            	<div class="alert alert-success"><?=$umsg?>  <?=Yii::t('app', $umsg=='1'?'Row has':'Rows have')?> <?=Yii::t('app', 'been Successfully Inserted')?></div>

            <?php } ?>
            <form  action="" id="user_frm" method="post" enctype="multipart/form-data" style="display:inline">

              <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                <div class="form-group">
					
<i><?=Yii::t('app', 'Note: File Type should be "csv"')?></i>
(
<i style="color:#F00; font-size:12px"><?=Yii::t('app', 'Required Fields*: first_name,last_name,username,email, user_type_id')?></i>
)
<br><br>
                	<label><?=Yii::t('app', 'CSV File')?></label>

                    <input type="file" name="csv_file" class="form-control" id="csv_file" data-validation="required">

                </div>

                <?= Html::submitButton(Yii::t('app', 'User CSV'), ['class' => 'btn btn-sm  btn-primary user_submit']) ?>
                
				</form>
                <?php if(isset($_FILES['csv_file'])){?>
                <form  action="" method="post" enctype="multipart/form-data" style="display:inline">
				  <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                  <input type="hidden" name="user_csv_upload" value="true">	
                   <?= Html::submitButton(Yii::t('app', 'Import File'), ['class' => 'btn btn-sm btn-primary customer_csv_upload']) ?>
                </form>
                 
                <?php } ?>
                <a class="btn btn-success btn-sm" target="_blank" href="../../livefactory/csv_files/user_template.csv" data-original-title="" title="">
                    <span class="fa fa-file-excel-o"></span>
                   	 <?=Yii::t('app', 'Download User CSV Import Template')?>
                    </a>
        </div>
	   <?php } ?>
        </div>

    </div>
    <br/><br/>
    		<div class="table-responsive">
    	<div class="ibox float-e-margins">
        <?php
			if(!empty($_FILES['project_csv_file'])){
				$_SESSION['customer_id']=!empty($_REQUEST['customer'])?$_REQUEST['customer']:'';
				echo ImportData::project_csv_table();
			}
		?>
    	<?php
			if(!empty($_FILES['task_csv_file'])){
				$_SESSION['project_id']=!empty($_REQUEST['project'])?$_REQUEST['project']:'';
				echo ImportData::task_csv_table();
			}
		?>
        <?php
			if(!empty($_FILES['defect_csv_file'])){
				$_SESSION['project_id1']=!empty($_REQUEST['project1'])?$_REQUEST['project1']:'';
				echo ImportData::defect_csv_table();
			}
		?>
        <?php
			if(!empty($_FILES['cus_csv_file'])){
				echo ImportData::customer_csv_table();
			}
		?>
        <?php
			if(!empty($_FILES['csv_file'])){
				echo ImportData::user_csv_table();
			}
		?>
        </div>
    </div>
 		</div>

    </div>
	
</div>