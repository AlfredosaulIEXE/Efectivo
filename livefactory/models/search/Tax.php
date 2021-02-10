<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Tax as TaxModel;

/**
 * Tax represents the model behind the search form about `livefactory\models\Tax`.
 */
class Tax extends TaxModel
{
    public function rules()
    {
        return [
            [['id', 'sort_order', 'active', 'added_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
            [['tax_percentage'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TaxModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tax_percentage' => $this->tax_percentage,
            'sort_order' => $this->sort_order,
            'active' => $this->active,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
