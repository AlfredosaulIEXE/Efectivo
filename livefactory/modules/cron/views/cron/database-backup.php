<?php
$params = require(__DIR__ . '/../../../../config/main-local.php');
$db=explode('dbname=',$params["components"]["db"]["dsn"]);
backup_tables('localhost',$params["components"]["db"]["username"],$params["components"]["db"]["password"],$db[1]);


/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
	$return.= 'SET FOREIGN_KEY_CHECKS=0;'."\n\n";
	$return.= 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";'."\n\n";
	$return.= 'SET time_zone = "+00:00";'."\n\n";
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'TRUNCATE TABLE '.$table.';';
		//$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE  IF NOT EXISTS  '.$table));
		//$return.= "\n\n".$row2[1].";\n\n";
		if(mysql_num_rows($result) > 0){
			$return.= 'INSERT INTO '.$table.' VALUES ';	
		}
		for ($i = 0; $i < $num_fields; $i++) 
		{
			$comma='';
			while($row = mysql_fetch_row($result))
			{
				$return.= $comma.'(';
				for($j=0; $j<$num_fields; $j++) 
				{
					
					
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (!empty($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.=$table=='auth_item'?'NULL':'""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ")";
				$comma=',';
				
			}
		}
		if(mysql_num_rows($result) > 0)
		$return.= ';';
		$return.="\n\n\n";
	}
	$return .='
	--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`), ADD KEY `rule_name` (`rule_name`), ADD KEY `type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`), ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `tbl_address`
--
ALTER TABLE `tbl_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_assignment_history`
--
ALTER TABLE `tbl_assignment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_company`
--
ALTER TABLE `tbl_company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_config_item`
--
ALTER TABLE `tbl_config_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cron_jobs`
--
ALTER TABLE `tbl_cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_currency`
--
ALTER TABLE `tbl_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_type`
--
ALTER TABLE `tbl_customer_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_defect`
--
ALTER TABLE `tbl_defect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_defect_priority`
--
ALTER TABLE `tbl_defect_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_defect_status`
--
ALTER TABLE `tbl_defect_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_defect_type`
--
ALTER TABLE `tbl_defect_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_email_template`
--
ALTER TABLE `tbl_email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_file`
--
ALTER TABLE `tbl_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_glocalization`
--
ALTER TABLE `tbl_glocalization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_note`
--
ALTER TABLE `tbl_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project`
--
ALTER TABLE `tbl_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_priority`
--
ALTER TABLE `tbl_project_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_status`
--
ALTER TABLE `tbl_project_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_type`
--
ALTER TABLE `tbl_project_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_user`
--
ALTER TABLE `tbl_project_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_region`
--
ALTER TABLE `tbl_region`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_session_details`
--
ALTER TABLE `tbl_session_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_state`
--
ALTER TABLE `tbl_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_priority`
--
ALTER TABLE `tbl_task_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_status`
--
ALTER TABLE `tbl_task_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_type`
--
ALTER TABLE `tbl_task_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_time_entry`
--
ALTER TABLE `tbl_time_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_type`
--
ALTER TABLE `tbl_user_type`
  ADD PRIMARY KEY (`id`);
  
	ALTER TABLE `auth_assignment`
ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;';
	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
	//save file
	$handle = fopen(__DIR__ . '/../../../../../livecrm/restore_db/livecrm_'.strtotime(date('Y/m/d G:i:s')).'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
}
?>