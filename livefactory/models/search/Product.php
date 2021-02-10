<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\Product as ProductModel;

/**
 * Product represents the model behind the search form about `livefactory\models\Product`.
 */
class Product extends ProductModel
{
    public function rules()
    {
        return [
            [['id', 'product_category_id', 'added_at', 'updated_at', 'active'], 'integer'],
            [['product_name', 'product_description'], 'safe'],
            [['product_price'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProductModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'active' => $this->active,
            'product_category_id' => $this->product_category_id,
            'product_price' => $this->product_price,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'product_description', $this->product_description]);

        return $dataProvider;
    }
}
