<?php
$params = require(__DIR__ . '/../../livefactory/config/main.php');
$db=explode('dbname=',$params["components"]["db"]["dsn"]);

backup_tables('localhost',$params["components"]["db"]["username"],$params["components"]["db"]["password"],$db[1]);



/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	$return_var = NULL;
	$output = NULL;
	$fullpath = '../restore_db/livecrm_new_'.date('m-d-Y_hia').'_'.time().'.sql';
	$command = "mysqldump -u $user -p$pass $name > $fullpath";
	
	exec($command, $output, $return_var);
	
	if($return_var) 
	{
		//echo "Undefined mysqldump! Please contact your admin!" > $fullpath;
		header("location:../web/index.php?r=liveobjects/setting/backup-db&backup=false");
	}
	else
	{
		header("location:../web/index.php?r=liveobjects/setting/backup-db&backup=true");
	}

	
}