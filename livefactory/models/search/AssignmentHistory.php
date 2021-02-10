<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\AssignmentHistory as AssignmentHistoryModel;

/**
 * AssignmentHistory represents the model behind the search form about `livefactory\models\AssignmentHistory`.
 */
class AssignmentHistory extends AssignmentHistoryModel
{
    public function rules()
    {
        return [
            [['id', 'from_user_id', 'to_user_id', 'entity_id', 'added_at', 'updated_at', 'assigned_by_user_id'], 'integer'],
            [['from', 'to', 'entity_type', 'notes'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = AssignmentHistoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'from' => $this->from,
            'to' => $this->to,
            'entity_id' => $this->entity_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
            'assigned_by_user_id' => $this->assigned_by_user_id,
        ]);

        $query->andFilterWhere(['like', 'entity_type', $this->entity_type])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
