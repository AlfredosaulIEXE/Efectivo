<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\TimeZoneCategory as TimeZoneCategoryModel;

/**
 * TimeZoneCategory represents the model behind the search form about `livefactory\models\TimeZoneCategory`.
 */
class TimeZoneCategory extends TimeZoneCategoryModel
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TimeZoneCategoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category]);

        return $dataProvider;
    }
}
