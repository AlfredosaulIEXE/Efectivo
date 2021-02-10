<?php

use livefactory\modules\pmt\controllers\ProjectController;

use livefactory\models\search\CommonModel;

use yii\helpers\Html;

use livefactory\models\search\Project as ProjectSearch;

use livefactory\models\FileModel;

/**

 * @var yii\web\View $this

 * @var common\models\Project $model

 */



$this->title = Yii::t('app', 'Project Discussion Board - ').$project->project_name;

$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['project-view', 'id' => $project->id]];

//$this->params['breadcrumbs'][] = ['label' => $model->project_name, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('app', 'Group Chat');

?>
<style>
.chat-discussion{ overflow-y: scroll;}
</style>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
var count = 0;
$(document).ready(function(e) {
		//alert($('.chat-discussion').height());
  
	$('.message-input').keydown(function(e){
		//alert(e.which);
		if(e.which==13){
			$('.insert').load('index.php?r=pmt/project/insert-chat&entity_type=project&entity_id=<?=$_GET['id']?>&message='+escape($('.message-input').val()));
			$('.message-input').val('');
			setTimeout(function(){
				$('.chat-discussion').scrollTop(20000);	
				flag = false;
			},1100)
		}
		
	})
	setInterval(function(){
	$.post('index.php?r=pmt/project/ajex-get-chat&entity_type=project&entity_id=<?=$_GET['id']?>',function(r){
		var data = r.split(',');
		
		$('.chat-discussion').html(data[0]);
		if(count != data[1]){
			setTimeout(function(){
				$('.chat-discussion').scrollTop(20000);	
				flag = false;
			},1100)	
		}
		count = data[1];
	});
	//alert(count);
		//$('.chat-discussion').scrollTop(5000);
	},1000);
	
	
});
</script>
<div class="insert"></div>
<div class="row">

        <div class="col-lg-12">



                <div class="ibox chat-view">



                    <div class="ibox-title">

                        <!--<small class="pull-right text-muted">Last message:  Mon Jan 26 2015 - 18:39:23</small>-->

                        <?= $this->title?>
                        <div class="ibox-tools">



						    <a class="collapse-link" onClick="window.location.href='index.php?r=pmt/project/project-view&id=<?=$_GET['id']?>'">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>





                    <div class="ibox-content">



                        <div class="row">



                            <div class="col-md-9 ">

                                <div class="chat-discussion">



                                   <!-- <div class="chat-message">

                                        <img class="message-avatar" src="img/a1.jpg" alt="" >

                                        <div class="message">

                                            <a class="message-author" href="#"> Michael Smith </a>

											<span class="message-date"> Mon Jan 26 2015 - 18:39:23 </span>

                                            <span class="message-content">

											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.

                                            </span>

                                        </div>

                                    </div>-->



                                </div>



                            </div>

                            <div class="col-md-3">

                                <div class="chat-users">





                                    <div class="users-list">
										<?php
											if(count($users) > 0){
												foreach($users  as $user){
										?>
                                        	<div class="chat-user">
                                            <?php
											$replace1=array(' ','.');
											$replace2=array('','');
											if(CommonModel::checkUserLoggedIn($user['id'])){?>
											<span class="pull-right label label-primary">Online</span>
                                            <?php } ?>
                                            <img class="chat-avatar" src="../users/user_<?=$user['id']?>.png" alt="" onerror="this.onerror=null;this.src='../users/noicon.jpg'" >

                                            <div class="chat-user-name">

                                                <a  href="javascript:void(0)" onclick="javascript:chatWith('<?=str_replace($replace1,$replace2,$user['first_name'])."_".trim(str_replace($replace1,$replace2,$user['last_name'])).'_'.$user['id']?>')"><?=$user['first_name']." ".$user['last_name']?></a>

                                            </div>

                                        </div>
                                        <?php
												}
										 } ?>





                                    </div>



                                </div>

                            </div>



                        </div>

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="chat-message-form">



                                    <div class="form-group">
                                        <textarea class="form-control message-input" name="message" placeholder="Enter message text"></textarea>
</form>
                                    </div>



                                </div>

                            </div>

                        </div>





                    </div>



                </div>

        </div>



    </div>