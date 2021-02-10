<?php



namespace livefactory\models;

use livefactory\models\User;
use livefactory\models\UserRole;
use livefactory\models\UserType;
use livefactory\models\Task;
use livefactory\models\TaskPriority;
use livefactory\models\TaskStatus;
use livefactory\models\TaskType;
use livefactory\models\Project;
use livefactory\models\ProjectPriority;
use livefactory\models\ProjectStatus;
use livefactory\models\ProjectType;
use livefactory\models\Defect;
use livefactory\models\DefectPriority;
use livefactory\models\DefectStatus;
use livefactory\models\DefectType;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\CustomerType;
use livefactory\models\Address;
use livefactory\models\Customer;
use livefactory\models\Contact;
use \DateTime;
use \DateTimeZone;

use Yii;

use yii\filters\VerbFilter;

use yii\db\Query;

class ImportData extends \yii\db\ActiveRecord

{


	public static function project_csv_table(){
		
		$table='<table cellpadding="5" width="100%" class="table  table-bordered">';
		if(strrchr($_FILES['project_csv_file']['name'], ".") =='.csv'){
			if($_FILES['project_csv_file']['tmp_name']){
			$handle = fopen($_FILES['project_csv_file']['tmp_name'], "r");
			$project_name=$project_description=$project_owner_id=-1;
			$row=1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$rowcolo = $row !=1 && (trim($data[$project_name])=='' || trim($data[$project_description])=='' || trim($data[$project_owner_id])=='')?'#FF0000':'';
			$table.="<tr style='background:$rowcolo'>";
			if($row ==1){
				for ($c=0; $c < $num; $c++) {
					if(trim($data[$c])=='project_name'){
						$project_name=$c;
					}
					if(trim($data[$c])=='project_description'){
						$project_description=$c;
					}
					if(trim($data[$c])=='project_owner_id'){
						$project_owner_id=$c;
					}
					$table.= "<th>".trim($data[$c])."</th>";
				}
			}else{
				for ($c=0; $c < $num; $c++) {
					$table.="<td>".trim($data[$c])."</td>" ;
				}
			}
			$table.="<tr>";
			  $row++;
		}
		$table.="</table>";
		move_uploaded_file($_FILES['project_csv_file']['tmp_name'],'project_csv.csv');
		fclose($handle);
			}
		}
		return $table;
	}
	public static function project_insert_by_csvfile($customer_id)
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$success=0;
		$error=0;
		$row=0;
		if(file_exists('project_csv.csv'))
		{
			$handle = fopen('project_csv.csv', "r");
			
			while (($data = fgetcsv($handle, 0, ",", "\"")) != FALSE) 
			{
				if($row==0)
				{
					$row++;
					continue;  
				}

				$model = new Project;

				$model->project_name=$data[0];
				$model->project_currency_id=$data[1];
				$model->project_description=$data[2];
				
				if(User::findByUsername($data[3])->id)
				$model->project_owner_id= User::findByUsername($data[3])->id;
				else
				$model->project_owner_id= $data[3];
				
				if(ProjectStatus::getProjectStatusIDByName($data[4])->id)
				$model->project_status_id = ProjectStatus::getProjectStatusIDByName($data[4])->id;
				else
				$model->project_status_id = $data[4];
				
				if(ProjectPriority::getProjectPriorityIDByName($data[5])->id)
				$model->project_priority_id = ProjectPriority::getProjectPriorityIDByName($data[5])->id;
				else
				$model->project_priority_id = $data[5];
				
				if(ProjectType::getProjectTypeIDByName($data[6])->id)
				$model->project_type_id = ProjectType::getProjectTypeIDByName($data[6])->id;
				else
				$model->project_type_id = $data[6];

				if(isset($data[9]) && !empty($data[9]))
					{
						$expected_start_datetime = new DateTime(trim($data[9]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($data[10]) && !empty($data[10]))
					{
						$expected_end_datetime = new DateTime(trim($data[10]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($data[8]) && !empty($data[8]))
					{
						$actual_start_datetime = new DateTime(trim($data[8]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($data[7]) && !empty($data[7]))
					{
						$actual_end_datetime = new DateTime(trim($data[7]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}


				$model->customer_id=$customer_id;				
				
				if($model->save())
				{
					$model->project_id = 'PROJECT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
					$model->update();
					$success++;
				}
				else
				{
					$error++;
				}
			}
		}
				
		fclose($handle);
		unlink('project_csv.csv');
			
		return array($success,$error);
	}


	public static function task_csv_table(){
		
		$table='<table cellpadding="5" width="100%" class="table  table-bordered">';
		if(strrchr($_FILES['task_csv_file']['name'], ".") =='.csv'){
			if($_FILES['task_csv_file']['tmp_name']){
			$handle = fopen($_FILES['task_csv_file']['tmp_name'], "r");
			$task_name=$task_description=$user_assigned_id=-1;
			$row=1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$rowcolo = $row !=1 && (trim($data[$task_name])=='' || trim($data[$task_description])=='')?'#FF0000':'';
			$table.="<tr style='background:$rowcolo'>";
			if($row ==1){
				for ($c=0; $c < $num; $c++) {
					if(trim($data[$c])=='task_name'){
						$task_name=$c;
					}
					if(trim($data[$c])=='task_description'){
						$task_description=$c;
					}
					if(trim($data[$c])=='user_assigned_id'){
						$user_assigned_id=$c;
					}
					$table.= "<th>".trim($data[$c])."</th>";
				}
			}else{
				for ($c=0; $c < $num; $c++) {
					$table.="<td>".trim($data[$c])."</td>" ;
				}
			}
			$table.="<tr>";
			  $row++;
		}
		$table.="</table>";
		move_uploaded_file($_FILES['task_csv_file']['tmp_name'],'task_csv.csv');
		fclose($handle);
			}
		}
		return $table;
	}
	public static function task_insert_by_csvfile($project_id)
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$success=0;
		$error=0;
		$row=0;
		if(file_exists('task_csv.csv'))
		{
			$handle = fopen('task_csv.csv', "r");
			
			while (($data = fgetcsv($handle, 0, ",", "\"")) != FALSE) 
			{
				if($row==0)
				{
					$row++;
					continue;  
				}

				$model = new Task;

				$model->task_name=$data[0];
				$model->task_description=$data[1];

				if(User::findByUsername($data[2])->id)
				$model->user_assigned_id= User::findByUsername($data[2])->id;
				else
				$model->user_assigned_id= $data[2];
				
				if(TaskStatus::getTaskStatusIDByName($data[3])->id)
				$model->task_status_id = TaskStatus::getTaskStatusIDByName($data[3])->id;
				else
				$model->task_status_id = $data[3];
				
				if(TaskPriority::getTaskPriorityIDByName($data[4])->id)
				$model->task_priority_id = TaskPriority::getTaskPriorityIDByName($data[4])->id;
				else
				$model->task_priority_id = $data[4];
				
				if(TaskType::getTaskTypeIDByName($data[5])->id)
				$model->task_type_id = TaskType::getTaskTypeIDByName($data[5])->id;
				else
				$model->task_type_id = $data[5];

				$model->task_progress=$data[6];
					

					if(isset($data[9]) && !empty($data[9]))
					{
						$expected_start_datetime = new DateTime(trim($data[9]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($data[10]) && !empty($data[10]))
					{
						$expected_end_datetime = new DateTime(trim($data[10]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($data[8]) && !empty($data[8]))
					{
						$actual_start_datetime = new DateTime(trim($data[8]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($data[7]) && !empty($data[7]))
					{
						$actual_end_datetime = new DateTime(trim($data[7]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}

				$model->time_spent=$data[11];
				$model->project_id=$project_id;
				
				if($model->save())
				{
					$model->task_id = 'TASK'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
					$model->update();
					$success++;
				}
				else
				{
					$error++;
				}
			}
		}
				
		fclose($handle);
		unlink('task_csv.csv');
			
		return array($success,$error);
	}
	
	public static function defect_csv_table(){
		
		$table='<table cellpadding="5" width="100%" class="table  table-bordered">';
		if(strrchr($_FILES['defect_csv_file']['name'], ".") =='.csv'){
			if($_FILES['defect_csv_file']['tmp_name']){
			$handle = fopen($_FILES['defect_csv_file']['tmp_name'], "r");
			$defect_name=$defect_description=$user_assigned_id=-1;
			$row=1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$rowcolo = $row !=1 && (trim($data[$defect_name])=='' || trim($data[$defect_description])=='')?'#FF0000':'';
			$table.="<tr style='background:$rowcolo'>";
			if($row ==1){
				for ($c=0; $c < $num; $c++) {
					if(trim($data[$c])=='defect_name'){
						$defect_name=$c;
					}
					if(trim($data[$c])=='defect_description'){
						$defect_description=$c;
					}
					if(trim($data[$c])=='user_assigned_id'){
						$user_assigned_id=$c;
					}
					$table.= "<th>".trim($data[$c])."</th>";
				}
			}else{
				for ($c=0; $c < $num; $c++) {
					$table.="<td>".trim($data[$c])."</td>" ;
				}
			}
			$table.="<tr>";
			  $row++;
		}
		$table.="</table>";
		move_uploaded_file($_FILES['defect_csv_file']['tmp_name'],'defect_csv.csv');
		fclose($handle);
			}
		}
		return $table;
	}
	public static function defect_insert_by_csvfile($project_id){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$success=0;
		$error=0;
		$row=0;
		if(file_exists('defect_csv.csv'))
		{
			$handle = fopen('defect_csv.csv', "r");
			
			while (($data = fgetcsv($handle, 0, ",", "\"")) != FALSE) 
			{
				if($row==0)
				{
					$row++;
					continue;  
				}

				$model = new Defect;

				$model->defect_name=$data[0];
				$model->defect_description=$data[1];

				if(User::findByUsername($data[2])->id)
				$model->user_assigned_id= User::findByUsername($data[2])->id;
				else
				$model->user_assigned_id= $data[2];
				
				if(DefectStatus::getDefectStatusIDByName($data[3])->id)
				$model->defect_status_id = DefectStatus::getDefectStatusIDByName($data[3])->id;
				else
				$model->defect_status_id = $data[3];
				
				if(DefectPriority::getDefectPriorityIDByName($data[4])->id)
				$model->defect_priority_id = DefectPriority::getDefectPriorityIDByName($data[4])->id;
				else
				$model->defect_priority_id = $data[4];
				
				if(DefectType::getDefectTypeIDByName($data[5])->id)
				$model->defect_type_id = DefectType::getDefectTypeIDByName($data[5])->id;
				else
				$model->defect_type_id = $data[5];


				$model->defect_progress=$data[6];
				if(isset($data[9]) && !empty($data[9]))
					{
						$expected_start_datetime = new DateTime(trim($data[9]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_start_datetime = $expected_start_datetime->getTimestamp();
					}

					if(isset($data[10]) && !empty($data[10]))
					{
						$expected_end_datetime = new DateTime(trim($data[10]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->expected_end_datetime = $expected_end_datetime->getTimestamp();
					}

					if(isset($data[8]) && !empty($data[8]))
					{
						$actual_start_datetime = new DateTime(trim($data[8]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_start_datetime = $actual_start_datetime->getTimestamp();
					}

					if(isset($data[7]) && !empty($data[7]))
					{
						$actual_end_datetime = new DateTime(trim($data[7]), new DateTimeZone(Yii::$app->params['TIME_ZONE']));
						$model->actual_end_datetime = $actual_end_datetime->getTimestamp();
					}
				$model->time_spent=$data[11];

				$model->project_id=$project_id;				
						
				if($model->save())
				{
					$model->defect_id = 'DEFECT'.str_pad($model->id, 9, "0", STR_PAD_LEFT);
					$model->update();
					$success++;
				}
				else
				{
					$error++;
				}
			}
		}
				
		fclose($handle);
		unlink('defect_csv.csv');
			
		return array($success,$error);
	}

	public static function customer_csv_table(){
		$table='<table cellpadding="5" width="100%" class="table  table-bordered">';
	if(strrchr($_FILES['cus_csv_file']['name'], ".")	 =='.csv'){
			if($_FILES['cus_csv_file']['tmp_name']){
			$handle = fopen($_FILES['cus_csv_file']['tmp_name'], "r");
			$first_name=$last_name=$customer_name=$mobile=$customer_owner_id=$address_1=$address_2=$zipcode=$country_id=$state_id=$customer_type_id=$email=$city_id=-1;
			$row=1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$rowcolo = $row !=1 &&  (trim($data[$first_name])=='' || trim($data[$last_name])=='' || trim($data[$customer_name])=='' || trim($data[$mobile])=='' || trim($data[$customer_owner_id])=='' || trim($data[$address_1])=='' || trim($data[$zipcode])=='' || trim($data[$country_id])=='' || trim($data[$state_id])=='' || trim($data[$customer_type_id])=='' || trim($data[$email])=='')?'#FF0000':'';
			$table.="<tr style='background:$rowcolo'>";
			if($row ==1){
				for ($c=0; $c < $num; $c++) {
					if(trim($data[$c])=='first_name'){
						$first_name=$c;
					}
					if(trim($data[$c])=='last_name'){
						$last_name=$c;
					}
					if(trim($data[$c])=='email'){
						$email=$c;
					}
					if(trim($data[$c])=='customer_name'){
						$customer_name=$c;
					}
					if(trim($data[$c])=='mobile'){
						$mobile=$c;
					}
					if(trim($data[$c])=='customer_owner_id'){
						$customer_owner_id=$c;
					}
					if(trim($data[$c])=='address_1'){
						$address_1=$c;
					}
					if(trim($data[$c])=='address_2'){
						$address_2=$c;
					}
					if(trim($data[$c])=='zipcode'){
						$zipcode=$c;
					}
					if(trim($data[$c])=='country_id'){
						$country_id=$c;
					}
					if(trim($data[$c])=='state_id'){
						$state_id=$c;
					}
					if(trim($data[$c])=='customer_type_id'){
						$customer_type_id=$c;
					}
					if(trim($data[$c])=='email'){
						$email=$c;
					}
					if(trim($data[$c])=='city_id'){
						$city_id=$c;
					}
					$table.= "<th>".trim($data[$c])."</th>";
				}
			}else{
				for ($c=0; $c < $num; $c++) {
					$table.="<td>".trim($data[$c])."</td>" ;
				}
			}
			$table.="<tr>";
			  $row++;
		}
		$table.="</table>";
		move_uploaded_file($_FILES['cus_csv_file']['tmp_name'],'customer_csv.csv');
		fclose($handle);
			}
		}
		return $table;	
	}
	public static function customer_insert_by_csvfile()
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$success=0;
		$error=0;
		$row=0;
		if(file_exists('customer_csv.csv'))
		{
			$handle = fopen('customer_csv.csv', "r");
			
			while (($data = fgetcsv($handle, 0, ",", "\"")) != FALSE) 
			{
				if($row==0)
				{
					$row++;
					continue;  
				}

				$model = new Customer;

				$model->first_name=$data[0];
				$model->last_name=$data[1];
				$model->customer_name=$data[2];
				$model->email=$data[3];
				$model->customer_type_id=$data[4];
				$model->mobile=$data[5];
				$model->customer_owner_id=$data[6];
				$model->phone=$data[13];
				$model->fax=$data[14];
	
				if($model->save())
				{
					$addmodel = new Address;

					$addmodel->address_1=$data[7];
					$addmodel->address_2=$data[8];
					$addmodel->zipcode=$data[9];
					$addmodel->country_id=$data[10];
					$addmodel->state_id=$data[11];
					$addmodel->city_id=$data[12];
					$addmodel->entity_id = $model->id;
					$addmodel->entity_type='customer';
					$addmodel->is_primary=1;

					if($addmodel->save())
					{
						$contactmodel = new Contact;

						$contactmodel->first_name=$data[0];
						$contactmodel->last_name=$data[1];
						$contactmodel->email=$data[3];
						$contactmodel->mobile=$data[5];
						$contactmodel->phone=$data[13];
						$contactmodel->fax=$data[14];
						$contactmodel->entity_id = $model->id;
						$contactmodel->entity_type='customer';
						$contactmodel->is_primary=1;

						if($contactmodel->save())
						{
							$success++;
						}
						else
						{
							$addmodel->delete();
							$model->delete();
							$error++;
						}
					}
					else
					{
						$model->delete();
						$error++;
					}
				}
				else
				{
					$error++;
				}
			}
		}
				
		fclose($handle);
		unlink('customer_csv.csv');
			
		return array($success,$error);
	}

	public static function user_csv_table(){
		$table='<table cellpadding="5" width="100%" class="table  table-bordered">';
		if(strrchr($_FILES['csv_file']['name'], ".")=='.csv'){
			if($_FILES['csv_file']['tmp_name']){
			$handle = fopen($_FILES['csv_file']['tmp_name'], "r");
			$first_name=$last_name=$username=$email=-1;
			$row=1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			$rowcolo = $row !=1 && (trim($data[$first_name])=='' || trim($data[$last_name])=='' || trim($data[$username])=='' || trim($data[$email])=='')?'#FF0000':'';
			$table.="<tr style='background:$rowcolo'>";
			if($row ==1){
				for ($c=0; $c < $num; $c++) {
					if(trim($data[$c])=='first_name'){
						$first_name=$c;
					}
					if(trim($data[$c])=='last_name'){
						$last_name=$c;
					}
					if(trim($data[$c])=='email'){
						$email=$c;
					}
					if(trim($data[$c])=='username'){
						$username=$c;
					}
					$table.= "<th>".trim($data[$c])."</th>";
				}
			}else{
				for ($c=0; $c < $num; $c++) {
					$table.="<td>".trim($data[$c])."</td>" ;
				}
			}
			$table.="<tr>";
			  $row++;
		}
		$table.="</table>";
		move_uploaded_file($_FILES['csv_file']['tmp_name'],'user_csv.csv');
		fclose($handle);
			}
		}
		return $table;
	}
	function user_insert_by_csvfile()
	{
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$success=0;
		$error=0;
		$row=0;
		if(file_exists('user_csv.csv'))
		{
			$handle = fopen('user_csv.csv', "r");
			
			while (($data = fgetcsv($handle, 0, ",", "\"")) != FALSE) 
			{
				if($row==0)
				{
					$row++;
					continue;  
				}

				$model = new User;

				$model->first_name=$data[0];
				$model->last_name=$data[1];
				$model->username=$data[2];
				$model->email=$data[3];
				$model->active=$data[4];
				$model->password_hash=$data[5];
				$model->user_type_id=$data[6];

				if($model->save())
				{
					$success++;
				}
				else
				{
					$error++;
				}
			}
		}
				
		fclose($handle);
		unlink('user_csv.csv');
			
		return array($success,$error);
	}

}

