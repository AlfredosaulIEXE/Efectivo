<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\QueueUsers as QueueUsersModel;

/**
 * QueueUsers represents the model behind the search form about `livefactory\models\QueueUsers`.
 */
class QueueUsers extends QueueUsersModel
{
    public function rules()
    {
        return [
            [['id', 'queue_id', 'user_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QueueUsersModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'queue_id' => $this->queue_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}
