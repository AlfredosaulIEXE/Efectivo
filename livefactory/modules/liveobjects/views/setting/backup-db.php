<?php
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\UserType;
use livefactory\models\UserRole;
use yii\helpers\ArrayHelper;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var livefactory\models\search\Address $searchModel
 */

$this->title = Yii::t ( 'app', 'Backup Database');
$this->params ['breadcrumbs'] [] = $this->title;
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
function getDBbackup(){
	var r = confirm('<?php echo Yii::t ( 'app', 'Are you Sure!' ); ?>');
	if(r){
		$.post('../include/before-restore-backup.php',function(){
		}).done(function(){
			document.frm.submit();
		})
		return false;
	}else{
		return false;	
	}
}
</script>
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
<div class="logo-index">
	<!--
	<div class="page-header">
		<h1><?= Html::encode($this->title) ?></h1>
	</div>
	-->
    <?php
		if(!empty($_GET['backup']) && $_GET['backup'] == 'true'){?>
			<div class="alert alert-success"><?=Yii::t ( 'app', 'Database backup Successfully Done!')?></div>
            <script>
				setTimeout(function(){
					window.location.href='index.php?r=liveobjects/setting/backup-db';
				},4000);
			</script>	
	<?php	}
	?>
	    <?php
		if(!empty($_GET['backup']) && $_GET['backup'] == 'false'){?>
			<div class="alert alert-danger"><?=Yii::t ( 'app', 'Database backup Failed! Make sure mysqldump binary is in your path. Contact your server admin.')?></div>
            <script>
				setTimeout(function(){
					window.location.href='index.php?r=liveobjects/setting/backup-db';
				},12000);
			</script>	
	<?php	}
	?>
    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Yii::t ( 'app', 'Backup Database' ); ?></h5>
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
                    <div class="row">
                    	<?php
							if(!empty($_GET['msg'])){?>
							<div class="alert alert-success"><?=$_GET['msg']?></div>	
						<?php	}
							
						?>
                      
                        
                        <div class="col-sm-6">
                            <div class="widget-head-color-box navy-bg p-lg text-center" style="height:260px">
                                <div class="m-b-md">
                                    <i class="fa fa-save fa-4x"></i>
                                    <h3 class="font-bold no-margins">
                                       <?=Yii::t ( 'app', 'Backup Database')?> 
                                    </h3>
                                    <small><?=Yii::t ( 'app', 'It will Create your Database Backup.')?> </small><br/>
									<small><?=Yii::t ( 'app', 'Make sure mysqldump binary is in your path. Contact your server admin if you face any issues.')?> </small><br/><br/>

                                   
                                </div>
                                 <a href="../include/db-backup.php" class="btn btn-success"><?=Yii::t ( 'app', 'Create Database Backup')?></a>
                            </div>
                        </div>
                        
                   		
                        
                     <!--   <div class="col-sm-6">
                        	<form method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                <input type="hidden" name="delete_backup_file" value="true">
                            <div class="widget yellow-bg p-lg text-center">
                                <div class="m-b-md">
                                    <i class="fa fa-trash fa-4x"></i>
                                    <h3 class="font-bold no-margins">
                                        <?=Yii::t ( 'app', 'Delete Backup File')?>
                                    </h3>
									<?php
										$dir = "../restore_db/";
										$list = array_diff(scandir($dir), array('..', '.','.gitignore'));
									?>
                                    <select name="delete_backup" class="form-control" style="color:#000">
									<?php                                    
										if(count($list) > 0){
										date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
										foreach($list as $item) {
											$name = strpos($item,'$')?end(explode('$',$item)):'';
											?>
										<option value="<?=$item; ?>"><?=$item; ?></option>
									<?php	}
										}
									?>
                                    </select>
                                    <small><?=Yii::t ( 'app', 'It will Delete Backup File.')?></small><br/><br/>
                                   
                                </div>
                                 <input type="submit" onClick="return confirm('<?php echo Yii::t ( 'app', 'Are you Sure!' ); ?>')" class="btn btn-danger" value="<?=Yii::t ( 'app', 'Delete')?>">
                            </div>
                        </form>
                        </div>-->
                   </div>
                   </div>
			</div>
</div>
