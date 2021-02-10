<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Defect as DefectModel;
use livefactory\models\DefectStatus;
use livefactory\models\File as FileModel;
use livefactory\models\Note as NoteModel;
use livefactory\models\DefectTime as DefectTimeModel;
use livefactory\models\TimeEntry;
use livefactory\models\History as HistoryModel;
use livefactory\models\AssignmentHistory as AssignmentHistoryModel;

/**
 * Defect represents the model behind the search form about `livefactory\models\Defect`.
 */
class Defect extends DefectModel
{
    public function rules()
    {
        return [
            [['id', 'user_assigned_id', 'defect_type_id','project_id', 'defect_status_id', 'defect_priority_id', 'parent_id', 'defect_progress', 'added_at', 'updated_at'], 'integer'],
            [['defect_id', 'defect_name', 'defect_description', 'time_spent', 'expected_start_datetime', 'expected_end_datetime', 'actual_start_datetime', 'actual_end_datetime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
		//if(Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'] =='Yes' && $_GET['Defect']['defect_status_id'] !=DefectStatus::_COMPLETED && $_GET['Defect']['defect_status_id'] !=DefectStatus::_CANCELLED){
			if(Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'] =='Yes'){
			if(!empty($_GET['sort'])){
				$query = DefectModel::find()->where("defect_status_id != ".DefectStatus::_COMPLETED." and defect_status_id !=".DefectStatus::_CANCELLED);
			if(Yii::$app->params['user_role'] !='admin'){
				$query = DefectModel::find()->where(" EXISTS(Select *
	FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.") and defect_status_id != ".DefectStatus::_COMPLETED." and defect_status_id !=".DefectStatus::_CANCELLED);
			}
			}else{
				$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where("defect_status_id != ".DefectStatus::_COMPLETED." and defect_status_id !=".DefectStatus::_CANCELLED);
				if(Yii::$app->params['user_role'] !='admin'){
					$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where(" EXISTS(Select *
		FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.") and defect_status_id != ".DefectStatus::_COMPLETED." and defect_status_id !=".DefectStatus::_CANCELLED);
				}
			}
		}else{
			if(!empty($_GET['sort'])){
				$query = DefectModel::find();
				if(Yii::$app->params['user_role'] !='admin'){
					$query = DefectModel::find()->where(" EXISTS(Select *
		FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.")");
				}
			}else{
				$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order');
				if(Yii::$app->params['user_role'] !='admin'){
					$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where(" EXISTS(Select *
		FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.")");
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
            'user_assigned_id' => $this->user_assigned_id,
            'project_id' => $this->project_id,
            'defect_status_id' => $this->defect_status_id,
			'defect_type_id' => $this->defect_type_id,
            'defect_priority_id' => $this->defect_priority_id,
            'parent_id' => $this->parent_id,
            'defect_progress' => $this->defect_progress,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', 'defect_id', $this->defect_id])
            ->andFilterWhere(['like', 'defect_name', $this->defect_name])
            ->andFilterWhere(['like', 'defect_description', $this->defect_description])
            ->andFilterWhere(['like', 'time_spent', $this->time_spent]);

		if($this->expected_start_datetime)
	$query->andFilterWhere(['between', 'expected_start_datetime', strtotime($this->expected_start_datetime), strtotime($this->expected_start_datetime)+24*60*60]);

	if($this->expected_end_datetime)
	$query->andFilterWhere(['between', 'expected_end_datetime', strtotime($this->expected_end_datetime), strtotime($this->expected_end_datetime)+24*60*60]);

	if($this->actual_start_datetime)
	$query->andFilterWhere(['between', 'actual_start_datetime', strtotime($this->actual_start_datetime), strtotime($this->actual_start_datetime)+24*60*60]);

	if($this->actual_end_datetime)
	$query->andFilterWhere(['between', 'actual_end_datetime', strtotime($this->actual_end_datetime), strtotime($this->actual_end_datetime)+24*60*60]);

        return $dataProvider;
    }
	public function searchNeedActions($params)
	{
		if(!empty($_GET['sort'])){
			$query = DefectModel::find()->where("defect_status_id=".DefectStatus::_NEEDSACTION." or defect_status_id=".DefectStatus::_INPROCESS);
			if(Yii::$app->params['user_role'] !='admin'){
				$query = DefectModel::find()->where("(defect_status_id=".DefectStatus::_NEEDSACTION." or defect_status_id=".DefectStatus::_INPROCESS.") and EXISTS(Select *
	FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.")");
			}
		}else{
		$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where("defect_status_id=".DefectStatus::_INPROCESS." or defect_status_id=".DefectStatus::_NEEDSACTION);
		if(Yii::$app->params['user_role'] !='admin'){
			$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where("(defect_status_id=".DefectStatus::_INPROCESS." or defect_status_id=".DefectStatus::_NEEDSACTION.") and EXISTS(Select *
FROM tbl_project_user  WHERE project_id =tbl_defect.project_id and user_id=".Yii::$app->user->identity->id.")");
		}
	}
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		$query->andFilterWhere ( [ 
				'id' => $this->id,
				'user_assigned_id' => $this->user_assigned_id,
				'project_id' => $this->project_id,
				'defect_status_id' => $this->defect_status_id,
				'defect_type_id' => $this->defect_type_id,
				'defect_priority_id' => $this->defect_priority_id,
				'expected_start_datetime' => $this->expected_start_datetime,
				'expected_end_datetime' => $this->expected_end_datetime,
				'actual_start_datetime' => $this->actual_start_datetime,
				'actual_end_datetime' => $this->actual_end_datetime,
				'parent_id' => $this->parent_id,
				'defect_progress' => $this->defect_progress,
				'added_at' => $this->added_at,
				'updated_at' => $this->updated_at
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'defect_id',
				$this->defect_id 
		] )->andFilterWhere ( [ 
				'like',
				'defect_name',
				$this->defect_name 
		] )->andFilterWhere ( [ 
				'like',
				'defect_description',
				$this->defect_description 
		] );
		
		//$query->orderBy ( 'defect_status_id' );
		
		return $dataProvider;
	}
	public function searchMyDefects($params)
	{
		
			if(Yii::$app->params['HIDE_COMPLETED_DEFECTS_BY_DEFAULT'] =='Yes' && $_GET['Defect']['defect_status_id'] !=DefectStatus::_COMPLETED){
			if(!empty($_GET['sort'])){
				$query = DefectModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id."  and defect_status_id != ".DefectStatus::_COMPLETED);
			}else{
			$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where("user_assigned_id=".Yii::$app->user->identity->id."  and defect_status_id != ".DefectStatus::_COMPLETED);
			}
			}else{
				if(!empty($_GET['sort'])){
					$query = DefectModel::find()->where("user_assigned_id=".Yii::$app->user->identity->id);	
				}else{
				$query = DefectModel::find()->joinWith('defectStatus')->joinWith('defectPriority')->orderBy('tbl_defect_status.sort_order,tbl_defect_priority.sort_order')->where("user_assigned_id=".Yii::$app->user->identity->id);	
				}
			}
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query,
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		$query->andFilterWhere ( [ 
				'id' => $this->id,
				'user_assigned_id' => $this->user_assigned_id,
				'project_id' => $this->project_id,
				'defect_status_id' => $this->defect_status_id,
				'defect_type_id' => $this->defect_type_id,
				'defect_priority_id' => $this->defect_priority_id,
				'expected_start_datetime' => $this->expected_start_datetime,
				'expected_end_datetime' => $this->expected_end_datetime,
				'actual_start_datetime' => $this->actual_start_datetime,
				'actual_end_datetime' => $this->actual_end_datetime,
				'parent_id' => $this->parent_id,
				'defect_progress' => $this->defect_progress,
				'added_at' => $this->added_at,
				'updated_at' => $this->updated_at
		] );
		
		$query->andFilterWhere ( [ 
				'like',
				'defect_id',
				$this->defect_id 
		] )->andFilterWhere ( [ 
				'like',
				'defect_name',
				$this->defect_name 
		] )->andFilterWhere ( [ 
				'like',
				'defect_description',
				$this->defect_description 
		] );
		
		//$query->orderBy ( 'defect_status_id' );
		
		return $dataProvider;
	}
	public function searchSubDefect($params, $entity_id)
	{
		$query = DefectModel::find ()->where ( [ 
				'parent_id' => $entity_id 
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
	public function searchDefectTime($params, $entity_id)
	{
		$query = TimeEntry::find()->where ( [ 
				'entity_id' => $entity_id,
				'entity_type'=>'defect' 
		] )->orderBy('end_time DESC');
		
		$dataProvider = new ActiveDataProvider ( [ 
				'query' => $query 
		] );
		
		if (! ($this->load ( $params ) && $this->validate ()))
		{
			return $dataProvider;
		}
		
		return $dataProvider;
	}
	public static function getSubDefectCount($entity_id)
	{
		return DefectModel::find ()->where ( [ 
				'parent_id' => $entity_id 
		] )->count();
	}
}
