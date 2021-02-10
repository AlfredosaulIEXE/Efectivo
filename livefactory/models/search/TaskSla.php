<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TaskSla as TaskSlaModel;

/**
 * TaskSla represents the model behind the search form about `livefactory\models\TaskSla`.
 */
class TaskSla extends TaskSlaModel
{
    public function rules()
    {
        return [
            [['id', 'task_priority_id', 'task_type_id', 'start_sla', 'end_sla'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TaskSlaModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'task_priority_id' => $this->task_priority_id,
			'task_type_id' => $this->task_priority_id,
            'start_sla' => $this->start_sla,
            'end_sla' => $this->end_sla,
        ]);

        return $dataProvider;
    }
}
