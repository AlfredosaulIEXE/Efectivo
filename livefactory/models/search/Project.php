<?php
namespace livefactory\models\search;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Project as ProjectModel;
use livefactory\models\ProjectStatus;
use livefactory\models\TaskStatus;
use livefactory\models\ProjectUser as ProjectUserModel;
use livefactory\models\File as FileModel;
use livefactory\models\Note as NoteModel;
use livefactory\models\Task as TaskModel;
use livefactory\models\History as HistoryModel;
use livefactory\models\Defect as DefectModel;
use livefactory\models\AssignmentHistory as AssignmentHistoryModel;
use livefactory\models\TimeEntry;
/**
 * Project represents the model behind the search form about `livefactory\models\Project`.
 */
class Project extends ProjectModel
{
	public $entity_type_value='project';
    public function rules()
    {
        return [
            [['id', 'project_type_id',  'project_id', 'project_status_id', 'project_priority_id', 'project_currency_id', 'customer_id', 'project_owner_id', 'added_at', 'updated_at','added_by_user_id','last_updated_by_user_id'], 'integer'],
            [['project_name', 'project_description', 'project_budget', 'project_progress', 'expected_start_datetime', 'expected_end_datetime', 'actual_start_datetime', 'actual_end_datetime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		if(Yii::$app->params['HIDE_COMPLETED_PROJECTS_BY_DEFAULT'] =='Yes'){
			if(!empty($_GET['sort'])){
				if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{
						$query = ProjectModel::find()->where("customer_id=".Yii::$app->user->identity->entity_id." and project_status_id !=".ProjectStatus::_COMPLETED." and project_status_id !=".ProjectStatus::_CANCELLED);
					}
					else	// User type is not customer
					{
						$query = ProjectModel::find()->where("EXISTS(Select * FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.") and project_status_id !=".ProjectStatus::_COMPLETED." and project_status_id !=".ProjectStatus::_CANCELLED);
					}
				}
				else
				{
					$query = ProjectModel::find()->where("project_status_id !=".ProjectStatus::_COMPLETED);
				}
			}else{
			if(Yii::$app->params['user_role'] !='admin'){
				if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
				{
					$query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order')->where("customer_id=".Yii::$app->user->identity->entity_id." and project_status_id !=".ProjectStatus::_COMPLETED." and project_status_id !=".ProjectStatus::_CANCELLED );
				}
				else	// User type is not customer
				{
					$query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order')->where("EXISTS(Select *	FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.") and project_status_id !=".ProjectStatus::_COMPLETED." and project_status_id !=".ProjectStatus::_CANCELLED);
				}
	
			}
			else
			{
				  $query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order')->where("project_status_id !=".ProjectStatus::_COMPLETED);
			}
		   }
		}else{
			if(!empty($_GET['sort'])){
			if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{	
						$query = ProjectModel::find()->where("customer_id=".Yii::$app->user->identity->entity_id);
					}
					else	// User type is not customer
					{
						$query = ProjectModel::find()->where("EXISTS(Select * FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")");
					}
				}
				else
				{
					$query = ProjectModel::find();
				}
			}else{
			if(Yii::$app->params['user_role'] !='admin'){
					if(Yii::$app->user->identity->userType->type=="Customer") // User type is customer
					{
						$query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order')->where("customer_id=".Yii::$app->user->identity->entity_id);
					}
					else	// User type is not customer
					{
						$query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order')->where("EXISTS(Select *	FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id=".Yii::$app->user->identity->id.")");
					}
				}
				else
				{
					$query = ProjectModel::find()->joinWith('projectStatus')->joinWith('projectPriority')->orderBy('tbl_project_status.sort_order,tbl_project_priority.sort_order');
				}
			}
		}
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'project_type_id' => $this->project_type_id,
			'project_id' => $this->project_id,
            'project_status_id' => $this->project_status_id,
            'project_priority_id' => $this->project_priority_id,
            'project_currency_id' => $this->project_currency_id,
            'customer_id' => $this->customer_id,
            'project_owner_id' => $this->project_owner_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
            'expected_start_datetime' => $this->expected_start_datetime,
            'expected_end_datetime' => $this->expected_end_datetime,
            'actual_start_datetime' => $this->actual_start_datetime,
            'actual_end_datetime' => $this->actual_end_datetime,
        ]);

        $query->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'project_description', $this->project_description])
            ->andFilterWhere(['like', 'project_budget', $this->project_budget])
            ->andFilterWhere(['like', 'project_progress', $this->project_progress]);

        return $dataProvider;
    }
	public function searchTask($params, $entity_id)

	{

		if($_GET['tasktab']){

						$query = TaskModel::find ()->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order')->where("(task_status_id='".TaskStatus::_NEEDSACTION."' OR  task_status_id='".TaskStatus::_INPROCESS."') and project_id=$entity_id");

	

		}else{

			$query = TaskModel::find ()->where ( [ 

					'project_id' => $entity_id 

			] )->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order');

		}

		

		$dataProvider = new ActiveDataProvider ( [ 

				'query' => $query 

		] );

		

		if (! ($this->load ( $params ) && $this->validate ()))

		{

			return $dataProvider;

		}

		

		return $dataProvider;

	}
	public function searchDefect($params, $entity_id)

	{

			$query = DefectModel::find ()->where ( [ 

					'project_id' => $entity_id 

			] )->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order');

		

		$dataProvider = new ActiveDataProvider ( [ 

				'query' => $query 

		] );

		

		if (! ($this->load ( $params ) && $this->validate ()))

		{

			return $dataProvider;

		}

		

		return $dataProvider;

	}
	
	public function searchProjectUser($params, $entity_id)

	{
		
		$query = ProjectUserModel::find ()->where ( [

				'project_id' => $entity_id 

		] );

		

		$dataProvider = new ActiveDataProvider ( [ 

				'query' => $query 

		] );

		

		if (! ($this->load ( $params ) && $this->validate ()))

		{

			return $dataProvider;

		}

		

		return $dataProvider;

	}
	public function searchTimesheet($params, $project_id)
	{
		$task_ids=array();
		$tasks = TaskModel::find()->where("project_id=".$project_id)->asArray()->all();
		if(count($tasks) > 0){
			foreach($tasks as $task){
				$task_ids[]=$task['id'];	
			}
			$ids = implode(",",$task_ids);
		}else{
			$ids =0;
		}
		//var_dump($ids);
		if(!empty($_GET['approved'])){
			$query = TimeEntry::find()->where("entity_id IN($ids) and entity_type='task' and approved ='1'")->orderBy('end_time DESC');
		}else if(!empty($_GET['pending'])){
			$query = TimeEntry::find()->where("entity_id IN($ids) and entity_type='task' and approved ='0'")->orderBy('end_time DESC');
		}else if(!empty($_GET['rejected'])){
			$query = TimeEntry::find()->where("entity_id IN($ids) and entity_type='task' and approved ='-1'")->orderBy('end_time DESC');
		}else{
			$query = TimeEntry::find()->where("entity_id IN($ids) and entity_type='task'")->orderBy('end_time DESC');
		}
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
}
