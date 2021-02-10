<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 */

$this->title = Yii::t('app','Mail Compose');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!empty($msg)){
?>
<div class="alert alert-success"><?=$msg?></div>
<?php } ?>
<div class="user-create">
    <div class="row">
            <!--<div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-content mailbox-content">
                        <div class="file-manager">
                            <a class="btn btn-block btn-primary compose-mail" href="#">Compose Mail</a>
                            <div class="space-25"></div>
                            <h5>Folders</h5>
                            <ul class="folder-list m-b-md" style="padding: 0">
                                <li><a href="#"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">16</span> </a></li>
                                <li><a href="#"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
                                <li><a href="#"> <i class="fa fa-certificate"></i> Important</a></li>
                                <li><a href="#"> <i class="fa fa-file-text-o"></i> Drafts <span class="label label-danger pull-right">2</span></a></li>
                                <li><a href="#"> <i class="fa fa-trash-o"></i> Trash</a></li>
                            </ul>
                            <h5>Categories</h5>
                            <ul class="category-list" style="padding: 0">
                                <li><a href="#"> <i class="fa fa-circle text-navy"></i> Work </a></li>
                                <li><a href="#"> <i class="fa fa-circle text-danger"></i> Documents</a></li>
                                <li><a href="#"> <i class="fa fa-circle text-primary"></i> Social</a></li>
                                <li><a href="#"> <i class="fa fa-circle text-info"></i> Advertising</a></li>
                                <li><a href="#"> <i class="fa fa-circle text-warning"></i> Clients</a></li>
                            </ul>

                            <h5 class="tag-title">Labels</h5>
                            <ul class="tag-list" style="padding: 0">
                                <li><a href="#"><i class="fa fa-tag"></i> Family</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Work</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Home</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Children</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Holidays</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Music</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Photography</a></li>
                                <li><a href="#"><i class="fa fa-tag"></i> Film</a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>-->
            <form  action="" method="post" enctype="multipart/form-data"  class="form-horizontal" >
               <?php Yii::$app->request->enableCsrfValidation = true; ?>
    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
            <div class="col-lg-12 animated fadeInRight">
            <div class="mail-box-header">
                <div class="pull-right tooltip-demo">
                    <!--<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>-->
                     <button class="btn btn-primary btn-sm" type="submit" id="send"><i class="fa fa-reply"></i> <?=Yii::t('app','Send')?></button>
                    <a href="index.php?r=user/user/view&id=<?=$user->id?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> <?=Yii::t('app','Discard')?></a>
                </div>
                <h2> <?=Yii::t('app','Compose mail')?>
                    
                </h2>
            </div>
                <div class="mail-box">


                <div class="mail-body">

                    
                        <div class="form-group"><label class="col-sm-2 control-label"><?=Yii::t('app','To')?>:</label>

                            <div class="col-sm-10"><input type="text" name="to" class="form-control" value="<?=$user->email?>" readonly></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label"><?=Yii::t('app','Cc')?>:</label>

                            <div class="col-sm-10"><input type="text" name="cc" class="form-control" value=""></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label"><?=Yii::t('app','Subject')?>:</label>

                            <div class="col-sm-10"><input type="text" id="subject" name="subject" class="form-control" value=""></div>
                        </div>

                </div>

                    <div class="mail-text h-200">

                        <div class="summernote">
                            <textarea class="form-control input-sm ckeditor" name="email_body" id="email_body" rows="8" style="width:100%"></textarea>
                        </div>
<div class="clearfix"></div>
                        </div>
                    <div class="mail-body text-right tooltip-demo">
                       <button class="btn btn-primary btn-sm" type="submit" id="send"><i class="fa fa-reply"></i> <?=Yii::t('app','Send')?></button>
                        <a href="index.php?r=user/user/view&id=<?=$user->id?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> <?=Yii::t('app','Discard')?></a>
                    </div>
                    <div class="clearfix"></div>



                </div>
                
                        </form>
            </div>
        </div>
</div>
<link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<style>
.cke_contents{max-height:250px}
</style>

    <script>
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
          $('#send').click(function(event){
			  var error='';
			  Remove_Error($('#subject'));
				if($('#subject').val() ==''){
					 error+=Add_Error($('#subject'),'Subject is Required!');
				}else{
					Remove_Error($('#subject'));
				}
				$('#cke_1_contents').parent().parent().removeAttr('style').next('.error').remove();
				sageLength = CKEDITOR.instances['email_body'].getData().replace(/<[^>]*>/gi, '').length;
				if(sageLength==0){
					error+=Add_ErrorTag($('#cke_1_contents').parent().parent(),'This Field is Required!');
				}
				if(error !=''){
					event.preventDefault();
				}else{
					return true	
				}
			})

        });

    </script>
