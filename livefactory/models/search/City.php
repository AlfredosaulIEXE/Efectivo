<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\City as CityModel;

/**
 * City represents the model behind the search form about `\livefactory\models\City`.
 */
class City extends CityModel
{
    public function rules()
    {
        return [
            [['id', 'active', 'state_id', 'country_id', 'added_at', 'updated_at'], 'integer'],
            [['city'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CityModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}
