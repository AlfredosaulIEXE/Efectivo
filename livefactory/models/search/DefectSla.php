<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\DefectSla as DefectSlaModel;

/**
 * DefectSla represents the model behind the search form about `livefactory\models\DefectSla`.
 */
class DefectSla extends DefectSlaModel
{
    public function rules()
    {
        return [
            [['id', 'defect_priority_id', 'defect_type_id', 'start_sla', 'end_sla'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DefectSlaModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'defect_priority_id' => $this->defect_priority_id,
			'defect_type_id' => $this->defect_type_id,
            'start_sla' => $this->start_sla,
            'end_sla' => $this->end_sla,
        ]);

        return $dataProvider;
    }
}
