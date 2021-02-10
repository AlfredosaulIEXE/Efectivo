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


 
$this->title = Yii::t ( 'app', 'License').' LiveCRM Version '.Yii::$app->params['APPLICATION_VERSION'];

$this->params ['breadcrumbs'] [] = $this->title;
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
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
		if(!empty($_GET['backup'])){?>
			<div class="alert alert-success"><?=Yii::t ( 'app', 'Database backup Successfully Done!')?></div>
            <script>
				setTimeout(function(){
					window.location.href='index.php?r=liveobjects/setting/restore-db';
				},2000);
			</script>	
	<?php	}
	?>
    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Yii::t ( 'app', 'License').' LiveCRM Version '.Yii::$app->params['APPLICATION_VERSION'] ; ?></h5>
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
                    	<?=file_get_contents(Yii::$app->params['LICENSE'])?>
                   </div>
			</div>
</div>
