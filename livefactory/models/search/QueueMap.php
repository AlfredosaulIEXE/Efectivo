<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\QueueMap as QueueMapModel;

/**
 * QueueMap represents the model behind the search form about `livefactory\models\QueueMap`.
 */
class QueueMap extends QueueMapModel
{
    public function rules()
    {
        return [
            [['id', 'department_id', 'ticket_category_id_2', 'ticket_category_id_2_id', 'queue_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QueueMapModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'department_id' => $this->department_id,
            'ticket_category_id_2' => $this->ticket_category_id_2,
            'ticket_category_id_2_id' => $this->ticket_category_id_2_id,
            'queue_id' => $this->queue_id,
        ]);

        return $dataProvider;
    }
}
