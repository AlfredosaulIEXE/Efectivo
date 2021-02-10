<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Sla as SlaModel;

/**
 * Sla represents the model behind the search form about `livefactory\models\Sla`.
 */
class Sla extends SlaModel
{
    public function rules()
    {
        return [
            [['id', 'ticket_priority_id', 'ticket_impact_id', 'sla'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SlaModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ticket_priority_id' => $this->ticket_priority_id,
            'ticket_impact_id' => $this->ticket_impact_id,
            'sla' => $this->sla,
        ]);

        return $dataProvider;
    }
}
