<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../livefactory/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../livefactory/config/main.php'),
    require(__DIR__ . '/../../livefactory/config/main-local.php'),
    require(__DIR__ . '/../../livefactory/config/datecontrol-module.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

$application = new yii\web\Application($config);

loadDBConfigItems();
loadAppLic();
setSessionParams();

function loadDBConfigItems()
{
		$items = livefactory\models\ConfigItem::find()->asArray()->all();
		foreach ($items as $item)
        {
            if ($item['config_item_name'])
			{
				Yii::$app->params[$item['config_item_name']] = $item['config_item_value'];
				Yii::$app->params[$item['config_item_name']."_description"] = $item['config_item_description'];
				if($item['config_item_name']=='LOCALE'){
					$_SESSION['LOCALE']=$item['config_item_value'];	
				}
				if($item['config_item_name']=='CHAT'){
					Yii::$app->params[$item['config_item_name']] = $item['active'];
				}
			}
			if ($item['config_item_value'] =='email_send')
			{
				Yii::$app->params[$item['config_item_name']] = $item['active'];
			}
        }

		
		$company = livefactory\models\Company::find()->asArray()->one();
		Yii::$app->params['company'] = $company;
		Yii::$app->params['address']= livefactory\models\search\Address::companyAddress($company['id']);

		$role = '';

		if(isset(Yii::$app->user->identity))
		$role = livefactory\models\AuthAssignment::find()->where("item_name='Admin' and user_id='".Yii::$app->user->identity->id."'")->asArray()->one();
		

		if(count($role) > 0){
			Yii::$app->params['user_role']= 'admin';
		}else{
			Yii::$app->params['user_role']= 'guest';	
		}

		//Yii::$app->params['invalid_ext'] = array("PHP", "PHP3", "PHP4", "PH", "PH1", "PH2", "PH3", "PH4", "JS", "EXE", "VB", "VBS", "CMD", "BAT", "CGI", "PERL", "PY");
		Yii::$app->params['invalid_ext'] = array("PH", "JS", "EXE", "VB", "CMD", "BAT", "CGI", "PERL", "PY"); //All extentions with mentioned keywords in them wll be blocked
}

function loadAppLic()
{
		////Application Licence
		$licences = livefactory\models\PrdLic::find()->where("prd_lic_status=1")->asArray()->all();
		if(count($licences) > 0){
			$lid= array();
			foreach($licences as $licence){
				$lid[]=$licence['id'];		
			}
			$ids =implode(',',$lid);
			$sql ="select * from tbl_prd_mdl_lic,tbl_prd_mdl,tbl_prd_lic where tbl_prd_mdl_lic.prd_lic_id=tbl_prd_lic.id and  tbl_prd_mdl_lic.prd_mdl_id=tbl_prd_mdl.id and tbl_prd_mdl_lic.prd_lic_id IN($ids)";
			$connection = \Yii::$app->db;
			$command=$connection->createCommand($sql);
			$modules=$command->queryAll();
			$moduleArray = array();
			foreach($modules as $module){
				$moduleArray[]=$module['mdl_name'];
			}
			Yii::$app->params['modules'] =$moduleArray;
		}

		
}

function setSessionParams()
{
		$_SESSION['SHOW_DEBUG_TOOLBAR']=Yii::$app->params['SHOW_DEBUG_TOOLBAR'];
}

$application->run();

ini_set('upload_max_filesize', Yii::$app->params['FILE_SIZE']);
ini_set('post_max_size',  Yii::$app->params['FILE_SIZE']);