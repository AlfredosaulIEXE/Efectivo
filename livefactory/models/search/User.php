<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\User as UserModel;
use livefactory\models\TimeEntry as TimeEntryModel;
use livefactory\models\Project as ProjectModel;
use livefactory\models\Task as TaskModel;
use livefactory\models\Queue as QueueModel;
use livefactory\models\Ticket as TicketModel;
use livefactory\models\Defect as DefectModel;


/**
 * User represents the model behind the search form about `\livefactory\models\User`.
 */
class User extends UserModel
{
    public function rules()
    {
        return [
            [['id','user_type_id', 'active','added_at', 'updated_at'], 'integer'],
            [['username', 'email',  'first_name', 'last_name', 'about'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = UserModel::find()->orderBy('first_name');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_type_id' => $this->user_type_id,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
           // ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'about', $this->about]);

        return $dataProvider;
    }
	public function searchTimeEntry($params, $user_id)
	{
		if(!empty($_GET['approved'])){
			$query = TimeEntryModel::find()->where (['user_id' => $user_id,'approved'=>'1'] )->orderBy('end_time DESC');
		}else if(!empty($_GET['pending'])){
			$query = TimeEntryModel::find()->where (['user_id' => $user_id,'approved'=>'0'] )->orderBy('end_time DESC');
		}else if(!empty($_GET['rejected'])){
			$query = TimeEntryModel::find()->where (['user_id' => $user_id,'approved'=>'-1'] )->orderBy('end_time DESC');
		}else{
			$query = TimeEntryModel::find()->where ( [ 
				'user_id' => $user_id
			] )->orderBy('end_time DESC');
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
	public function searchTask($params, $user_id)
	{
		$query = TaskModel::find()->joinWith('taskStatus')->joinWith('taskPriority')->orderBy('tbl_task_status.sort_order,tbl_task_priority.sort_order')->where("user_assigned_id=$user_id");
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
	public function searchProject($params, $user_id)
	{
		$query = ProjectModel::find()->where(" EXISTS(Select *
	FROM tbl_project_user  WHERE project_id =tbl_project.id and user_id =$user_id) or project_owner_id=$user_id");
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
	public function searchQueue($params, $user_id)
	{
		$query = QueueModel::find()->where("queue_supervisor_user_id=$user_id ");
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
	public function searchTicket($params, $user_id)
	{
		$query = TicketModel::find()->where("user_assigned_id =$user_id");
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
	
	public function searchDefect($params, $user_id)
	{
		$query = DefectModel::find()->where("user_assigned_id =$user_id");
		
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
