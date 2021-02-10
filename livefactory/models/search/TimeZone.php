<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TimeZone as TimeZoneModel;

/**
 * TimeZone represents the model behind the search form about `livefactory\models\TimeZone`.
 */
class TimeZone extends TimeZoneModel
{
    public function rules()
    {
        return [
            [['id', 'category_id', 'added_at'], 'integer'],
            [['zone'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TimeZoneModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'added_at' => $this->added_at,
        ]);

        $query->andFilterWhere(['like', 'zone', $this->zone]);

        return $dataProvider;
    }
}
